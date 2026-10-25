<?php

namespace Tests\Feature\Learning;

use App\Domain\Catalog\Enums\LessonType;
use App\Domain\Catalog\Models\Course;
use App\Domain\Catalog\Models\CourseSection;
use App\Domain\Catalog\Models\Lesson;
use App\Domain\Identity\Enums\RoleName;
use App\Domain\Identity\Models\Role;
use App\Domain\Instructor\Models\InstructorProfile;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class FreeLearningTest extends TestCase
{
    use RefreshDatabase;

    public function test_free_enrollment_is_idempotent_and_article_progress_persists(): void
    {
        $student = $this->student();
        [$course, $lesson] = $this->course();

        $this->actingAs($student)
            ->post(route('learning.enroll', $course))
            ->assertRedirect(route('learning.index'));

        $this->actingAs($student)
            ->post(route('learning.enroll', $course))
            ->assertRedirect(route('learning.index'));

        $this->assertDatabaseCount('enrollments', 1);

        $this->actingAs($student)
            ->get(route('learning.show', [$course, $lesson]))
            ->assertOk();

        $this->actingAs($student)
            ->put(route('learning.complete', [$course, $lesson]))
            ->assertRedirect();

        $this->assertDatabaseCount('lesson_progress', 1);
    }

    public function test_paid_course_cannot_be_enrolled_without_checkout(): void
    {
        $student = $this->student();
        [$course] = $this->course(199000);

        $this->actingAs($student)
            ->post(route('learning.enroll', $course))
            ->assertForbidden();

        $this->assertDatabaseCount('enrollments', 0);
    }

    public function test_other_student_cannot_read_or_complete_private_lesson(): void
    {
        $owner = $this->student();
        $other = $this->student();
        [$course, $lesson] = $this->course();

        $this->actingAs($owner)
            ->post(route('learning.enroll', $course));

        $this->actingAs($other)
            ->get(route('learning.show', [$course, $lesson]))
            ->assertForbidden();

        $this->actingAs($other)
            ->put(route('learning.complete', [$course, $lesson]))
            ->assertForbidden();
    }

    public function test_video_without_content_cannot_be_marked_complete(): void
    {
        $student = $this->student();
        [$course, $lesson] = $this->course();

        $video = Lesson::factory()
            ->for($lesson->section, 'section')
            ->create([
                'type' => LessonType::Video,
                'content' => null,
                'sort_order' => 2,
            ]);

        $this->actingAs($student)
            ->post(route('learning.enroll', $course));

        $this->actingAs($student)
            ->put(route('learning.complete', [$course, $video]))
            ->assertStatus(422);

        $this->assertDatabaseCount('lesson_progress', 0);
    }

    private function student(): User
    {
        $user = User::factory()->create();

        $role = Role::query()
            ->where('name', RoleName::Student)
            ->firstOrFail();

        $user->roles()->attach($role);

        return $user;
    }

    /** @return array{Course, Lesson} */
    private function course(int $price = 0): array
    {
        $profile = InstructorProfile::factory()->create();

        $course = Course::factory()
            ->for($profile, 'instructor')
            ->published()
            ->create(['price_idr' => $price]);

        $section = CourseSection::factory()
            ->for($course)
            ->create();

        $lesson = Lesson::factory()
            ->for($section, 'section')
            ->create([
                'type' => LessonType::Article,
                'content' => 'Materi latihan yang dapat dibaca siswa.',
                'sort_order' => 1,
            ]);

        return [$course, $lesson];
    }
}
