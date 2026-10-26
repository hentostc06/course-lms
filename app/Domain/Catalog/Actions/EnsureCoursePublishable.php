<?php

namespace App\Domain\Catalog\Actions;

use App\Domain\Catalog\Enums\LessonType;
use App\Domain\Catalog\Models\Course;
use App\Domain\Catalog\Models\Lesson;
use App\Domain\Instructor\Enums\InstructorStatus;
use Illuminate\Validation\ValidationException;

/**
 * Milestone 1 supports readable text articles only. Video and document uploads
 * are not wired to a protected player; do not publish paid/empty placeholders.
 */
final class EnsureCoursePublishable
{
    public function handle(Course $course): void
    {
        if ($course->instructor?->status !== InstructorStatus::Approved) {
            throw ValidationException::withMessages([
                'instructor' => 'Instruktur harus aktif dan disetujui sebelum kursus diterbitkan.',
            ]);
        }

        if (! $course->category?->is_active) {
            throw ValidationException::withMessages([
                'category' => 'Pilih kategori aktif sebelum mengajukan kursus.',
            ]);
        }

        if (! is_array($course->outcomes) || count($course->outcomes) === 0) {
            throw ValidationException::withMessages([
                'outcomes' => 'Isi minimal satu hasil belajar sebelum kursus dapat diterbitkan.',
            ]);
        }

        $lessons = $course->sections()->with('lessons')->get()
            ->flatMap(fn ($section) => $section->lessons);

        if ($lessons->isEmpty()) {
            throw ValidationException::withMessages([
                'curriculum' => 'Tambahkan minimal satu artikel pembelajaran sebelum mengajukan review.',
            ]);
        }

        if ($lessons->contains(fn (Lesson $lesson): bool =>
            $lesson->type !== LessonType::Article
            || ! is_string($lesson->content)
            || trim($lesson->content) === ''
        )) {
            throw ValidationException::withMessages([
                'curriculum' => 'Semua materi harus berupa artikel yang memiliki isi. Video/dokumen belum didukung pemutar belajar dan tidak boleh diterbitkan.',
            ]);
        }
    }
}
