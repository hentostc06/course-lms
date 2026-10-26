<?php

namespace App\Domain\Catalog\Actions;

use App\Domain\Catalog\Enums\CourseStatus;
use App\Domain\Catalog\Models\Course;
use App\Domain\Identity\Models\AdminAuditEvent;
use App\Models\User;
use Illuminate\Support\Facades\DB;
use Illuminate\Validation\ValidationException;

final class SubmitCourseForReview
{
    public function handle(User $actor, Course $course): void
    {
        abort_unless(
            $actor->hasVerifiedEmail() && $actor->hasRole('admin'),
            403
        );

        DB::transaction(function () use ($actor, $course): void {
            $locked = Course::query()
                ->lockForUpdate()
                ->findOrFail($course->id);

            if (! in_array(
                $locked->status,
                [CourseStatus::Draft, CourseStatus::Rejected],
                true
            )) {
                throw ValidationException::withMessages([
                    'status' => 'Hanya kursus Draft atau Ditolak yang dapat diajukan kembali.',
                ]);
            }

            app(EnsureCoursePublishable::class)->handle($locked);

            $from = $locked->status->value;

            $locked->forceFill([
                'status' => CourseStatus::PendingReview,
                'published_at' => null,
                'review_feedback' => null,
                'reviewed_at' => null,
                'reviewed_by' => null,
            ])->save();

            DB::table('course_review_events')->insert([
                'course_id' => $locked->id,
                'actor_id' => $actor->id,
                'from_status' => $from,
                'to_status' => CourseStatus::PendingReview->value,
                'note' => 'Kursus diajukan untuk moderasi oleh administrator.',
                'created_at' => now(),
            ]);

            AdminAuditEvent::record(
                $actor,
                'course.submitted',
                'course',
                $locked->id,
                $from,
                CourseStatus::PendingReview->value
            );
        });
    }
}
