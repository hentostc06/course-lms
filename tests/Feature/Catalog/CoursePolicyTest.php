<?php

namespace Tests\Feature\Catalog;

use App\Domain\Catalog\Models\Course;
use App\Domain\Identity\Enums\RoleName;
use App\Domain\Identity\Models\Role;
use App\Domain\Instructor\Enums\InstructorStatus;
use App\Domain\Instructor\Models\InstructorProfile;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Gate;
use Tests\TestCase;

class CoursePolicyTest extends TestCase
{
    use RefreshDatabase;

    public function test_approved_instructor_can_update_own_editable_course(): void
    {
        [$owner, $profile] = $this->approvedInstructor();
        $course = Course::factory()->for($profile, 'instructor')->create();

        $this->assertTrue(Gate::forUser($owner)->allows('update', $course));
    }

    public function test_instructor_cannot_update_another_instructors_course(): void
    {
        [$owner, $ownerProfile] = $this->approvedInstructor();
        [$other] = $this->approvedInstructor();
        $course = Course::factory()->for($ownerProfile, 'instructor')->create();

        $this->assertTrue(Gate::forUser($owner)->allows('update', $course));
        $this->assertFalse(Gate::forUser($other)->allows('update', $course));
    }

    public function test_suspended_instructor_cannot_mutate_own_draft(): void
    {
        [$owner, $profile] = $this->approvedInstructor();
        $profile->update(['status' => InstructorStatus::Suspended]);
        $course = Course::factory()->for($profile, 'instructor')->create();

        foreach (['update', 'submit', 'delete'] as $ability) {
            $this->assertFalse(Gate::forUser($owner)->allows($ability, $course));
        }
    }

    public function test_owner_without_instructor_role_cannot_mutate_own_draft(): void
    {
        [$owner, $profile] = $this->approvedInstructor();
        $owner->roles()->detach($this->role(RoleName::Instructor));
        $course = Course::factory()->for($profile, 'instructor')->create();

        foreach (['update', 'submit', 'delete'] as $ability) {
            $this->assertFalse(Gate::forUser($owner)->allows($ability, $course));
        }
    }

    public function test_instructor_cannot_update_published_course_but_admin_can(): void
    {
        [$owner, $profile] = $this->approvedInstructor();
        $admin = User::factory()->create();
        $admin->roles()->attach($this->role(RoleName::Admin));
        $course = Course::factory()->published()->for($profile, 'instructor')->create();

        $this->assertFalse(Gate::forUser($owner)->allows('update', $course));
        $this->assertTrue(Gate::forUser($admin)->allows('update', $course));
    }

    /** @return array{User, InstructorProfile} */
    private function approvedInstructor(): array
    {
        $user = User::factory()->create();
        $user->roles()->attach($this->role(RoleName::Instructor));
        $profile = InstructorProfile::factory()->for($user)->create([
            'status' => InstructorStatus::Approved,
            'approved_at' => now(),
        ]);

        return [$user, $profile];
    }

    private function role(RoleName $role): Role
    {
        return Role::query()->where('name', $role)->firstOrFail();
    }
}
