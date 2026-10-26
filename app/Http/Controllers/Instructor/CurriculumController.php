<?php

namespace App\Http\Controllers\Instructor;

use App\Domain\Catalog\Enums\LessonType;
use App\Domain\Catalog\Models\Course;
use App\Domain\Catalog\Models\CourseSection;
use App\Domain\Catalog\Models\Lesson;
use App\Http\Controllers\Controller;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Validation\Rule;
use Inertia\Inertia;
use Inertia\Response;

class CurriculumController extends Controller
{
    public function show(Request $request, Course $course): Response
    {
        $this->authorizeCourse($request, $course);
        $course->load('sections.lessons');

        return Inertia::render('instructor/Curriculum', [
            'course' => [
                'slug' => $course->slug,
                'title' => $course->title,
                'status' => $course->status->value,
                'sections' => $course->sections->map(fn (CourseSection $section) => [
                    'id' => $section->id,
                    'title' => $section->title,
                    'lessons' => $section->lessons->map(fn ($lesson) => [
                        'id' => $lesson->id,
                        'title' => $lesson->title,
                        'type' => $lesson->type->value,
                        'durationSeconds' => $lesson->duration_seconds,
                        'isPreview' => $lesson->is_preview,
                        'content' => $lesson->content,
                    ])->all(),
                ])->all(),
            ],
            'lessonTypes' => [LessonType::Article->value],
        ]);
    }

    public function storeSection(Request $request, Course $course): RedirectResponse
    {
        $this->authorizeCourse($request, $course);
        $data = $request->validate(['title' => ['required', 'string', 'min:3', 'max:180']]);
        DB::transaction(function () use ($course, $request, $data): void {
            $course = Course::query()->lockForUpdate()->findOrFail($course->id);
            $this->authorizeCourse($request, $course);
            $course->sections()->create([
                ...$data,
                'sort_order' => (int) $course->sections()->max('sort_order') + 1,
            ]);
        });

        return back()->with('success', 'Bagian kurikulum ditambahkan.');
    }

    public function storeLesson(Request $request, Course $course, CourseSection $section): RedirectResponse
    {
        $this->authorizeCourse($request, $course);
        abort_unless($section->course_id === $course->id, 404);

        $data = $request->validate([
            'title' => ['required', 'string', 'min:3', 'max:180'],
            // No private video/document storage or viewer exists in this milestone.
            'type' => ['required', Rule::in([LessonType::Article->value])],
            'content' => ['required', 'string', 'max:20000'],
            'duration_seconds' => ['required', 'integer', 'min:0', 'max:86400'],
            'is_preview' => ['required', 'boolean'],
        ]);
        DB::transaction(function () use ($course, $section, $request, $data): void {
            $course = Course::query()->lockForUpdate()->findOrFail($course->id);
            $this->authorizeCourse($request, $course);
            $section->lessons()->create([
                ...$data,
                'sort_order' => (int) $section->lessons()->max('sort_order') + 1,
            ]);

            $this->recalculateDuration($course);
        });

        return back()->with('success', 'Materi ditambahkan.');
    }

    public function updateLesson(Request $request, Course $course, CourseSection $section, Lesson $lesson): RedirectResponse
    {
        $this->authorizeCourse($request, $course);
        abort_unless($section->course_id === $course->id && $lesson->course_section_id === $section->id, 404);
        $data = $request->validate([
            'title' => ['required', 'string', 'min:3', 'max:180'],
            'type' => ['required', Rule::in([LessonType::Article->value])],
            'content' => ['required', 'string', 'max:20000'],
            'duration_seconds' => ['required', 'integer', 'min:0', 'max:86400'],
            'is_preview' => ['required', 'boolean'],
        ]);
        abort_unless(trim($data['content']) !== '', 422, 'Isi artikel wajib diisi.');

        DB::transaction(function () use ($course, $section, $lesson, $request, $data): void {
            $locked = Course::query()->lockForUpdate()->findOrFail($course->id);
            $this->authorizeCourse($request, $locked);
            $item = Lesson::query()->whereKey($lesson->id)
                ->where('course_section_id', $section->id)->firstOrFail();
            $item->update($data);
            $this->recalculateDuration($locked);
        });

        return back()->with('success', 'Artikel diperbarui.');
    }

    public function deleteLesson(Request $request, Course $course, CourseSection $section, Lesson $lesson): RedirectResponse
    {
        $this->authorizeCourse($request, $course);
        abort_unless($section->course_id === $course->id && $lesson->course_section_id === $section->id, 404);

        DB::transaction(function () use ($course, $section, $lesson, $request): void {
            $locked = Course::query()->lockForUpdate()->findOrFail($course->id);
            $this->authorizeCourse($request, $locked);
            $item = Lesson::query()->whereKey($lesson->id)
                ->where('course_section_id', $section->id)->firstOrFail();
            if (DB::table('lesson_progress')->where('lesson_id', $item->id)->exists()) {
                throw \Illuminate\Validation\ValidationException::withMessages([
                    'lesson' => 'Materi dengan progres siswa tidak boleh dihapus.',
                ]);
            }
            $item->delete();
            $this->recalculateDuration($locked);
        });

        return back()->with('success', 'Materi dihapus.');
    }

    private function recalculateDuration(Course $course): void
    {
        $seconds = $course->sections()->with('lessons')->get()->sum(
            fn (CourseSection $section) => $section->lessons->sum('duration_seconds')
        );
        $course->update(['total_duration_minutes' => (int) ceil($seconds / 60)]);
    }

    private function authorizeCourse(Request $request, Course $course): void
    {
        abort_unless($request->user()?->can('update', $course), 403);
    }
}
