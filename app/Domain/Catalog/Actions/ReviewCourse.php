<?php

namespace App\Domain\Catalog\Actions;

use App\Domain\Catalog\Enums\CourseStatus;
use App\Domain\Catalog\Models\Course;
use App\Domain\Identity\Models\AdminAuditEvent;
use App\Domain\Instructor\Models\InstructorProfile;
use App\Models\User;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\Rule;

class ReviewCourse
{
    public function handle(User $actor, Course $course, string $decision, ?string $reason = null): void
    {
        abort_unless($actor->hasVerifiedEmail() && $actor->hasRole('admin'), 403);
        $data = Validator::make(['decision' => $decision, 'reason' => $reason === null ? null : trim($reason)], [
            'decision' => ['required', Rule::in(['approve', 'reject', 'archive', 'restore'])],
            'reason' => ['required_unless:decision,approve', 'nullable', 'string', 'min:10', 'max:2000'],
        ])->validate();

        DB::transaction(function () use ($actor, $course, $decision, $data): void {
            $instructor = InstructorProfile::query()->lockForUpdate()->findOrFail($course->instructor_id);
            $locked = Course::query()->lockForUpdate()->findOrFail($course->id);
            $expected = match ($decision) {
                'archive' => CourseStatus::Published,
                'restore' => CourseStatus::Archived,
                default => CourseStatus::PendingReview,
            };
            abort_unless($locked->status === $expected, 409, 'Status kursus sudah berubah. Muat ulang daftar.');
            if ($decision === 'approve') {
                app(EnsureCoursePublishable::class)->handle($locked);
            }

            $target = match ($decision) {
                'approve' => CourseStatus::Published,
                'reject' => CourseStatus::Rejected,
                'restore' => CourseStatus::Draft,
                default => CourseStatus::Archived,
            };
            $locked->forceFill([
                'status' => $target,
                'published_at' => $decision === 'approve' ? now() : null,
                'review_feedback' => $decision === 'approve' ? null : $data['reason'],
                'reviewed_at' => now(),
                'reviewed_by' => $actor->id,
            ])->save();
            DB::table('course_review_events')->insert([
                'course_id' => $locked->id, 'actor_id' => $actor->id,
                'from_status' => $expected->value, 'to_status' => $target->value,
                'note' => $data['reason'] ?? 'Kursus disetujui setelah peninjauan.', 'created_at' => now(),
            ]);
            AdminAuditEvent::record($actor, 'course.'.$decision, 'course', $locked->id,
                $expected->value, $target->value, $data['reason'] ?? 'Kursus disetujui setelah peninjauan.');
        });
    }
}
