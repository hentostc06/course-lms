<?php

namespace App\Http\Controllers\Instructor;

use App\Domain\Catalog\Enums\CourseStatus;
use App\Domain\Catalog\Actions\EnsureCoursePublishable;
use App\Domain\Catalog\Models\Course;
use App\Http\Controllers\Controller;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Validation\ValidationException;

class CourseSubmissionController extends Controller
{
    public function __invoke(Request $request, Course $course): RedirectResponse
    {
        abort_unless($request->user()->can('submit', $course), 403);

        DB::transaction(function () use ($course, $request): void {
            $course = Course::query()->lockForUpdate()->findOrFail($course->id);
            abort_unless($request->user()->can('submit', $course), 403);
            app(EnsureCoursePublishable::class)->handle($course);
            $previous = $course->status->value;
            $course->forceFill([
                'status' => CourseStatus::PendingReview,
                'published_at' => null,
                'review_feedback' => null,
                'reviewed_at' => null,
                'reviewed_by' => null,
            ])->save();
            DB::table('course_review_events')->insert([
                'course_id' => $course->id,
                'actor_id' => $request->user()->id,
                'from_status' => $previous,
                'to_status' => CourseStatus::PendingReview->value,
                'note' => null,
                'created_at' => now(),
            ]);
        });

        return to_route('instructor.courses.index')->with('success', 'Kursus dikirim untuk moderasi.');
    }
}
