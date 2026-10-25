<?php

namespace Tests\Feature\Instructor;

use App\Domain\Catalog\Enums\CourseStatus;
use App\Domain\Catalog\Models\Category;
use App\Domain\Catalog\Models\Course;
use App\Domain\Catalog\Models\CourseSection;
use App\Domain\Identity\Enums\RoleName;
use App\Domain\Identity\Models\Role;
use App\Domain\Instructor\Enums\InstructorStatus;
use App\Domain\Instructor\Models\InstructorProfile;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\DB;
use Inertia\Testing\AssertableInertia as Assert;
use Tests\TestCase;

class InstructorWorkflowTest extends TestCase
{
    use RefreshDatabase;

    public function test_verified_user_can_apply_but_cannot_publish_before_approval(): void
    {
        $user = User::factory()->create();
        $this->actingAs($user)->post(route('instructor.onboarding.store'), [
            'headline' => 'Auditor internal profesional',
            'bio' => str_repeat('Berpengalaman memimpin audit internal. ', 3),
        ])->assertRedirect(route('instructor.onboarding'));

        $this->assertDatabaseHas('instructor_profiles', [
            'user_id' => $user->id,
            'status' => InstructorStatus::Pending->value,
        ]);
        $this->assertTrue($user->hasRole(RoleName::Instructor));
        $this->actingAs($user)->get(route('instructor.courses.create'))->assertForbidden();
    }

    public function test_approved_instructor_can_create_edit_and_build_curriculum(): void
    {
        [$owner, $profile] = $this->instructor();
        $category = Category::factory()->create();
        $data = $this->validCourse($category->id);

        $this->actingAs($owner)->post(route('instructor.courses.store'), $data)
            ->assertRedirect();
        $course = Course::query()->sole();
        $this->assertSame($profile->id, $course->instructor_id);
        $this->assertSame(CourseStatus::Draft, $course->status);

        $this->actingAs($owner)->patch(route('instructor.courses.update', $course), [
            ...$data, 'title' => 'Audit Internal Berbasis Risiko untuk Auditor',
            'instructor_id' => 9999, 'status' => 'published',
        ])->assertRedirect();
        $course->refresh();
        $this->assertSame(CourseStatus::Draft, $course->status);
        $this->assertSame($profile->id, $course->instructor_id);

        $this->actingAs($owner)->post(route('instructor.sections.store', $course), [
            'title' => 'Persiapan Audit',
        ])->assertRedirect();
        $section = CourseSection::query()->sole();
        $this->actingAs($owner)->post(route('instructor.lessons.store', [$course, $section]), [
            'title' => 'Pengenalan standar audit',
            'type' => 'article', 'content' => 'Materi audit yang bersifat privat.',
            'duration_seconds' => 600, 'is_preview' => false,
        ])->assertRedirect();

        $this->assertEquals(10, $course->fresh()->total_duration_minutes);
        $this->actingAs($owner)->get(route('instructor.courses.curriculum', $course))
            ->assertOk()->assertInertia(fn (Assert $page) => $page
                ->component('instructor/Curriculum')->has('course.sections', 1));
    }

    public function test_other_instructor_cannot_modify_course_or_sections(): void
    {
        [$owner, $profile] = $this->instructor();
        [$stranger] = $this->instructor();
        $course = Course::factory()->for($profile, 'instructor')->create();
        $section = CourseSection::factory()->for($course)->create();

        $this->actingAs($stranger)->get(route('instructor.courses.edit', $course))->assertForbidden();
        $this->actingAs($stranger)->post(route('instructor.lessons.store', [$course, $section]), [
            'title' => 'Tidak berhak', 'type' => 'article',
            'duration_seconds' => 10, 'is_preview' => false,
        ])->assertForbidden();
        $this->actingAs($stranger)->post(route('instructor.courses.submit', $course))->assertForbidden();
    }

    public function test_only_admin_can_moderate_and_every_decision_is_audited(): void
    {
        [$owner, $profile] = $this->instructor();
        [$stranger] = $this->instructor();
        $course = Course::factory()->for($profile, 'instructor')->create();
        $section = CourseSection::factory()->for($course)->create();
        $this->actingAs($owner)->post(route('instructor.courses.submit', $course))
            ->assertSessionHasErrors('curriculum');

        $section->lessons()->create([
            'title' => 'Materi awal', 'type' => 'article',
            'duration_seconds' => 120, 'is_preview' => false,
            'sort_order' => 1,
        ]);
        $this->actingAs($owner)->post(route('instructor.courses.submit', $course))->assertRedirect();
        $this->assertSame(CourseStatus::PendingReview, $course->fresh()->status);
        $this->assertDatabaseCount('course_review_events', 1);
        $this->actingAs($stranger)->post(route('moderation.courses.decide', $course), [
            'decision' => 'approve',
        ])->assertForbidden();

        $admin = User::factory()->create();
        $admin->roles()->attach($this->role(RoleName::Admin));
        $this->actingAs($admin)->post(route('moderation.courses.decide', $course), [
            'decision' => 'reject', 'reason' => 'Capaian pembelajaran perlu diperinci.',
        ])->assertRedirect();
        $this->assertSame(CourseStatus::Rejected, $course->fresh()->status);
        $this->assertDatabaseCount('course_review_events', 2);

        $this->actingAs($owner)->post(route('instructor.courses.submit', $course))->assertRedirect();
        $this->actingAs($admin)->post(route('moderation.courses.decide', $course), [
            'decision' => 'approve',
        ])->assertRedirect();
        $this->assertSame(CourseStatus::Published, $course->fresh()->status);
        $this->assertNotNull($course->fresh()->published_at);
        $this->assertDatabaseCount('course_review_events', 4);
        $this->actingAs($admin)->post(route('moderation.courses.decide', $course), [
            'decision' => 'approve',
        ])->assertStatus(409);
        $this->actingAs($owner)->get(route('instructor.courses.edit', $course))->assertForbidden();
    }

    public function test_admin_can_create_category_and_approve_pending_instructor(): void
    {
        $applicant = User::factory()->create();
        $this->actingAs($applicant)->post(route('instructor.onboarding.store'), [
            'headline' => 'Praktisi audit internal dan kepatuhan',
            'bio' => str_repeat('Mendampingi proses audit dan pengembangan sistem manajemen. ', 2),
        ])->assertRedirect();
        $profile = $applicant->instructorProfile()->firstOrFail();
        $admin = User::factory()->create();
        $admin->roles()->attach($this->role(RoleName::Admin));

        $this->actingAs($applicant)->post(route('moderation.instructors.approve', $profile))
            ->assertForbidden();
        $this->actingAs($admin)->post(route('moderation.categories.store'), [
            'name' => 'Audit dan Kepatuhan', 'description' => 'Kursus sistem manajemen.',
        ])->assertRedirect();
        $this->assertDatabaseHas('categories', ['name' => 'Audit dan Kepatuhan']);
        $this->actingAs($admin)->post(route('moderation.instructors.approve', $profile))
            ->assertRedirect();
        $this->assertSame(InstructorStatus::Approved, $profile->fresh()->status);
        $this->assertDatabaseCount('instructor_review_events', 1);
        $this->actingAs($admin)->post(route('moderation.instructors.approve', $profile))
            ->assertStatus(409);
        $this->actingAs($applicant)->get(route('instructor.courses.create'))->assertOk();
    }

    public function test_only_administrator_can_access_filament_panel(): void
    {
        [$instructor] = $this->instructor();
        $admin = User::factory()->create();
        $admin->roles()->attach($this->role(RoleName::Admin));

        $this->assertFalse($instructor->canAccessPanel(\Filament\Facades\Filament::getPanel('admin')));
        $this->assertTrue($admin->canAccessPanel(\Filament\Facades\Filament::getPanel('admin')));
    }

    private function validCourse(int $categoryId): array
    {
        return [
            'category_id' => $categoryId,
            'title' => 'Audit Internal Terapan untuk Auditor',
            'summary' => 'Pelatihan praktis untuk memahami dan menerapkan teknik audit internal.',
            'description' => str_repeat('Belajar audit berdasarkan praktik industri dengan panduan yang terstruktur. ', 2),
            'price_idr' => 299000,
            'level' => 'beginner', 'language' => 'id',
            'outcomes' => ['Mampu mengidentifikasi risiko dan pengendalian audit'],
            'requirements' => [], 'audience' => [], 'thumbnail_url' => null,
        ];
    }

    private function instructor(): array
    {
        $user = User::factory()->create();
        $user->roles()->attach($this->role(RoleName::Instructor));
        $profile = InstructorProfile::factory()->for($user)->create();

        return [$user, $profile];
    }

    private function role(RoleName $role): Role
    {
        return Role::query()->where('name', $role)->firstOrFail();
    }
}
