<?php

use Illuminate\Foundation\Inspiring;
use Illuminate\Support\Facades\Artisan;

Artisan::command('inspire', function () {
    $this->comment(Inspiring::quote());
})->purpose('Display an inspiring quote');

// Safe production-data audit: SELECT only, never writes/repairs rows.
Artisan::command('lms:audit-flows', function (): void {
    $this->info('COURSE LMS — audit alur (READ-ONLY, tanpa perubahan DB)');
    $published = 0;
    $invalid = 0;

    \App\Domain\Catalog\Models\Course::query()
        ->where('status', \App\Domain\Catalog\Enums\CourseStatus::Published)
        ->with(['category', 'instructor'])
        ->chunkById(100, function ($courses) use (&$published, &$invalid): void {
            foreach ($courses as $course) {
                $published++;
                try {
                    app(\App\Domain\Catalog\Actions\EnsureCoursePublishable::class)
                        ->handle($course);
                    if ($course->published_at === null || $course->published_at->isFuture()) {
                        $this->warn("[INVALID PUBLICATION DATE] #{$course->id} — {$course->title}");
                        $invalid++;
                    }
                } catch (\Illuminate\Validation\ValidationException $exception) {
                    $this->warn("[INVALID PUBLISHED COURSE] #{$course->id} — {$course->title}");
                    foreach ($exception->errors() as $messages) {
                        foreach ($messages as $message) {
                            $this->line('  - '.$message);
                        }
                    }
                    $invalid++;
                }
            }
        });

    $blockedEnrollments = \Illuminate\Support\Facades\DB::table('enrollments as e')
        ->join('courses as c', 'c.id', '=', 'e.course_id')
        ->join('instructor_profiles as i', 'i.id', '=', 'c.instructor_id')
        ->join('categories as cat', 'cat.id', '=', 'c.category_id')
        ->where('e.status', 'active')
        ->where(function ($query): void {
            $query->where('c.status', '<>', 'published')
                ->orWhereNull('c.published_at')
                ->orWhere('c.published_at', '>', now())
                ->orWhereNotNull('c.deleted_at')
                ->orWhere('i.status', '<>', 'approved')
                ->orWhere('cat.is_active', false);
        })->count();

    $crossCourseProgress = \Illuminate\Support\Facades\DB::table('lesson_progress as lp')
        ->join('enrollments as e', 'e.id', '=', 'lp.enrollment_id')
        ->join('lessons as l', 'l.id', '=', 'lp.lesson_id')
        ->join('course_sections as cs', 'cs.id', '=', 'l.course_section_id')
        ->whereColumn('cs.course_id', '<>', 'e.course_id')
        ->count();

    $paidEnrollments = \Illuminate\Support\Facades\DB::table('enrollments as e')
        ->join('courses as c', 'c.id', '=', 'e.course_id')
        ->where('e.status', 'active')
        ->where('c.price_idr', '>', 0)
        ->count();

    $this->table(['Pemeriksaan', 'Jumlah'], [
        ['Kursus status Published', $published],
        ['Published tidak memenuhi readiness', $invalid],
        ['Enrollment aktif tetapi course tidak bisa dibuka', $blockedEnrollments],
        ['Progress lesson berbeda course dari enrollment', $crossCourseProgress],
        ['Enrollment kursus berbayar (perlu bukti pembayaran)', $paidEnrollments],
    ]);
    $this->info('Audit selesai. TIDAK ADA PERUBAHAN DATA.');
})->purpose('Audit alur Course LMS dan anomali database secara read-only');
