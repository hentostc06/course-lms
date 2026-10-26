<?php

namespace App\Http\Controllers\Moderation;

use App\Domain\Catalog\Enums\CourseStatus;
use App\Domain\Catalog\Models\Course;
use App\Domain\Identity\Enums\RoleName;
use App\Http\Controllers\Controller;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
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

        app(\App\Domain\Catalog\Actions\ReviewCourse::class)->handle($request->user(), $course, $data['decision'], $data['reason'] ?? null);

        return back()->with('success', 'Keputusan moderasi berhasil disimpan.');
    }

    private function ensureAdmin(Request $request): void
    {
        abort_unless($request->user()?->hasRole(RoleName::Admin), 403);
    }
}
