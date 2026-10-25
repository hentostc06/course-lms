<?php

namespace App\Http\Controllers\Learning;

use App\Domain\Catalog\Enums\LessonType;
use App\Domain\Catalog\Models\Course;
use App\Domain\Catalog\Models\Lesson;
use App\Domain\Identity\Enums\RoleName;
use App\Http\Controllers\Controller;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Inertia\Inertia;
use Inertia\Response;

class LearningController extends Controller
{
    public function index(Request $request): Response
    {
        $this->student($request);

        $rows = DB::table('enrollments')
            ->where('student_id', $request->user()->id)
            ->where('status', 'active')
            ->orderByDesc('id')
            ->get();

        $items = [];

        foreach ($rows as $row) {
            $course = Course::query()
                ->published()
                ->with('sections.lessons')
                ->whereKey($row->course_id)
                ->first();

            if (! $course) {
                continue;
            }

            $lessons = $course->sections
                ->flatMap(fn ($section) => $section->lessons);

            $articles = $lessons
                ->filter(fn ($lesson) => $this->readable($lesson));

            $completed = DB::table('lesson_progress')
                ->where('enrollment_id', $row->id)
                ->whereIn('lesson_id', $articles->pluck('id')->all())
                ->count();

            $items[] = [
                'slug' => $course->slug,
                'title' => $course->title,
                'summary' => $course->summary,
                'thumbnailUrl' => $course->thumbnail_url,
                'total' => $articles->count(),
                'completed' => $completed,
                'firstLessonId' => $articles->first()?->id,
            ];
        }

        return Inertia::render('marketplace/MyLearning', [
            'courses' => $items,
        ]);
    }

    public function enroll(Request $request, Course $course): RedirectResponse
    {
        $this->student($request);

        $course = Course::query()
            ->published()
            ->findOrFail($course->id);

        abort_unless(
            $course->price_idr === 0,
            403,
            'Kursus berbayar memerlukan pembayaran terverifikasi.'
        );

        DB::table('enrollments')->insertOrIgnore([
            'student_id' => $request->user()->id,
            'course_id' => $course->id,
            'status' => 'active',
            'created_at' => now(),
            'updated_at' => now(),
        ]);

        return redirect()->route('learning.index');
    }

    public function show(
        Request $request,
        Course $course,
        Lesson $lesson
    ): Response {
        $enrollment = $this->access($request, $course, $lesson);

        $course->load('sections.lessons');

        $done = DB::table('lesson_progress')
            ->where('enrollment_id', $enrollment->id)
            ->pluck('lesson_id')
            ->all();

        return Inertia::render('marketplace/LessonPlayer', [
            'course' => [
                'title' => $course->title,
                'slug' => $course->slug,
            ],
            'lesson' => [
                'id' => $lesson->id,
                'title' => $lesson->title,
                'type' => $lesson->type->value,
                'content' => $this->readable($lesson)
                    ? $lesson->content
                    : null,
                'available' => $this->readable($lesson),
                'completed' => in_array($lesson->id, $done),
            ],
            'sections' => $course->sections->map(
                fn ($section) => [
                    'id' => $section->id,
                    'title' => $section->title,
                    'lessons' => $section->lessons->map(
                        fn ($item) => [
                            'id' => $item->id,
                            'title' => $item->title,
                            'type' => $item->type->value,
                            'completed' => in_array($item->id, $done),
                        ]
                    )->values()->all(),
                ]
            )->values()->all(),
        ]);
    }

    public function complete(
        Request $request,
        Course $course,
        Lesson $lesson
    ): RedirectResponse {
        $enrollment = $this->access($request, $course, $lesson);

        abort_unless(
            $this->readable($lesson),
            422,
            'Materi ini belum tersedia.'
        );

        DB::table('lesson_progress')->updateOrInsert([
            'enrollment_id' => $enrollment->id,
            'lesson_id' => $lesson->id,
        ], [
            'completed_at' => now(),
            'updated_at' => now(),
        ]);

        return back();
    }

    private function student(Request $request): void
    {
        abort_unless(
            $request->user()?->hasRole(RoleName::Student),
            403
        );
    }

    private function access(
        Request $request,
        Course $course,
        Lesson $lesson
    ): \stdClass {
        $this->student($request);

        Course::query()->published()->findOrFail($course->id);

        abort_unless(
            $lesson->section()
                ->where('course_id', $course->id)
                ->exists(),
            404
        );

        $enrollment = DB::table('enrollments')
            ->where('student_id', $request->user()->id)
            ->where('course_id', $course->id)
            ->where('status', 'active')
            ->first();

        if ($enrollment === null) {
            abort(403, 'Kamu belum mengikuti kursus ini.');
        }

        return $enrollment;
    }

    private function readable(Lesson $lesson): bool
    {
        return $lesson->type === LessonType::Article
            && is_string($lesson->content)
            && trim($lesson->content) !== '';
    }
}
