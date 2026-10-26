<?php

namespace App\Domain\Catalog\Policies;

use App\Domain\Catalog\Enums\CourseStatus;
use App\Domain\Catalog\Models\Course;
use App\Domain\Identity\Enums\RoleName;
use App\Domain\Instructor\Enums\InstructorStatus;
use App\Models\User;

class CoursePolicy
{
    public function before(User $user, string $ability): ?bool
    {
        // Administrator changes are authorized by the dedicated, audited admin
        // actions. Do not give admin users write access via instructor routes.
        return $ability === 'view' && $user->hasRole(RoleName::Admin) ? true : null;
    }

    public function view(?User $user, Course $course): bool
    {
        if (
            $course->status === CourseStatus::Published
            && $course->published_at !== null
            && ! $course->published_at->isFuture()
            && $course->instructor->status === InstructorStatus::Approved
        ) {
            return true;
        }

        return $user !== null && $this->owns($user, $course);
    }

    public function create(User $user): bool
    {
        return $user->hasRole(RoleName::Instructor)
            && $user->instructorProfile?->status === InstructorStatus::Approved;
    }

    public function update(User $user, Course $course): bool
    {
        return $this->create($user)
            && $this->owns($user, $course)
            && $course->status->isEditableByInstructor();
    }

    public function submit(User $user, Course $course): bool
    {
        return $this->update($user, $course);
    }

    public function delete(User $user, Course $course): bool
    {
        return $this->update($user, $course);
    }

    private function owns(User $user, Course $course): bool
    {
        return $user->instructorProfile !== null
            && $user->instructorProfile->is($course->instructor);
    }
}
