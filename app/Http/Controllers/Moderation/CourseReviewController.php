<?php

namespace App\Http\Controllers\Moderation;

use App\Domain\Catalog\Enums\CourseStatus;
use App\Domain\Catalog\Models\Course;
use App\Domain\Identity\Enums\RoleName;
use App\Domain\Instructor\Enums\InstructorStatus;
use App\Http\Controllers\Controller;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Validation\Rule;
use Inertia\Inertia;
use Inertia\Response;

class CourseReviewController extends Controller
{
    public function index(Request $request): Response
    {
        $this->ensureAdmin($request);

        $courses = Course::query()->where('status', CourseStatus::PendingReview)
            ->with(['instructor.user:id,name', 'category:id,name'])
            ->oldest('updated_at')->get();

        return Inertia::render('moderation/CourseReviews', [
            'courses' => $courses->map(fn (Course $course) => [
                'slug' => $course->slug,
                'title' => $course->title,
                'summary' => $course->summary,
                'instructor' => $course->instructor->user->name,
                'category' => $course->category->name,
                'updatedAt' => $course->updated_at?->toDateString(),
            ])->all(),
        ]);
    }

    public function decide(Request $request, Course $course): RedirectResponse
    {
        $this->ensureAdmin($request);
        $data = $request->validate([
            'decision' => ['required', Rule::in(['approve', 'reject'])],
            'reason' => ['required_if:decision,reject', 'nullable', 'string', 'min:10', 'max:2000'],
        ]);

        DB::transaction(function () use ($request, $course, $data): void {
            // Lock current row and validate the transition again inside the transaction.
            $locked = Course::query()->lockForUpdate()->findOrFail($course->id);
            abort_unless($locked->status === CourseStatus::PendingReview, 409, 'Kursus sudah diproses.');

            $approved = $data['decision'] === 'approve';
            if ($approved) {
                abort_unless($locked->instructor->status === InstructorStatus::Approved, 422, 'Instruktur tidak aktif.');
            }
            $target = $approved ? CourseStatus::Published : CourseStatus::Rejected;
            $locked->update([
                'status' => $target,
                'published_at' => $approved ? now() : null,
                'review_feedback' => $approved ? null : $data['reason'],
                'reviewed_at' => now(),
                'reviewed_by' => $request->user()->id,
            ]);

            DB::table('course_review_events')->insert([
                'course_id' => $locked->id,
                'actor_id' => $request->user()->id,
                'from_status' => CourseStatus::PendingReview->value,
                'to_status' => $target->value,
                'note' => $data['reason'] ?? null,
                'created_at' => now(),
            ]);
        });

        return back()->with('success', 'Keputusan moderasi berhasil disimpan.');
    }

    private function ensureAdmin(Request $request): void
    {
        abort_unless($request->user()?->hasRole(RoleName::Admin), 403);
    }
}
