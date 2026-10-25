<?php

namespace App\Http\Controllers\Marketplace;

use App\Domain\Catalog\Enums\CourseLevel;
use App\Domain\Catalog\Models\Category;
use App\Domain\Catalog\Models\Course;
use App\Http\Controllers\Controller;
use App\Http\Requests\Marketplace\CourseCatalogRequest;
use Illuminate\Database\Eloquent\Builder;
use Inertia\Inertia;
use Inertia\Response;

class CourseCatalogController extends Controller
{
    public function index(CourseCatalogRequest $request): Response
    {
        $filters = $request->validated();

        $courses = Course::query()
            ->published()
            ->with(['category:id,name,slug', 'instructor.user:id,name'])
            ->when($filters['q'] ?? null, fn (Builder $query, string $search) => $query
                ->where(fn (Builder $query) => $query
                    ->whereLike('title', "%{$search}%")
                    ->orWhereLike('summary', "%{$search}%")))
            ->when($filters['category'] ?? null, fn (Builder $query, string $slug) => $query
                ->whereHas('category', fn (Builder $query) => $query->where('slug', $slug)))
            ->when($filters['level'] ?? null, fn (Builder $query, string $level) => $query->where('level', $level))
            ->when(($filters['price'] ?? null) === 'free', fn (Builder $query) => $query->where('price_idr', 0))
            ->when(($filters['price'] ?? null) === 'paid', fn (Builder $query) => $query->where('price_idr', '>', 0))
            ->when(
                ($filters['sort'] ?? 'newest') === 'price_asc',
                fn (Builder $query) => $query->orderBy('price_idr'),
                fn (Builder $query) => $query->when(
                    ($filters['sort'] ?? 'newest') === 'price_desc',
                    fn (Builder $query) => $query->orderByDesc('price_idr'),
                    fn (Builder $query) => $query->orderByDesc('published_at'),
                ),
            )
            ->paginate(12)
            ->withQueryString()
            ->through(fn (Course $course): array => $this->courseCard($course));

        return Inertia::render('marketplace/Catalog', [
            'courses' => $courses,
            'categories' => Category::query()->active()->orderBy('sort_order')->orderBy('name')->get(['name', 'slug']),
            'filters' => [
                'q' => $filters['q'] ?? '',
                'category' => $filters['category'] ?? '',
                'level' => $filters['level'] ?? '',
                'price' => $filters['price'] ?? '',
                'sort' => $filters['sort'] ?? 'newest',
            ],
            'levels' => array_map(
                fn (CourseLevel $level): array => ['value' => $level->value, 'label' => $level->label()],
                CourseLevel::cases(),
            ),
        ]);
    }

    public function show(string $slug): Response
    {
        $course = Course::query()
            ->published()
            ->with([
                'category:id,name,slug',
                'instructor.user:id,name',
                'sections.lessons:id,course_section_id,title,type,duration_seconds,is_preview,sort_order',
            ])
            ->where('slug', $slug)
            ->firstOrFail();

        return Inertia::render('marketplace/CourseDetail', [
            'course' => $this->courseDetail($course),
        ]);
    }

    /** @return array<string, mixed> */
    private function courseCard(Course $course): array
    {
        return [
            'slug' => $course->slug,
            'title' => $course->title,
            'summary' => $course->summary,
            'thumbnailUrl' => $course->thumbnail_url,
            'priceIdr' => $course->price_idr,
            'level' => $course->level->label(),
            'durationMinutes' => $course->total_duration_minutes,
            'category' => $course->category->name,
            'categorySlug' => $course->category->slug,
            'instructor' => $course->instructor->user->name,
        ];
    }

    /** @return array<string, mixed> */
    private function courseDetail(Course $course): array
    {
        $sections = [];

        foreach ($course->sections as $section) {
            $lessons = [];

            foreach ($section->lessons as $lesson) {
                $lessons[] = [
                    'id' => $lesson->id,
                    'title' => $lesson->title,
                    'type' => $lesson->type->value,
                    'durationSeconds' => $lesson->duration_seconds,
                    'isPreview' => $lesson->is_preview,
                ];
            }

            $sections[] = [
                'id' => $section->id,
                'title' => $section->title,
                'description' => $section->description,
                'lessons' => $lessons,
            ];
        }

        return [
            ...$this->courseCard($course),
            'description' => $course->description,
            'outcomes' => $course->outcomes ?? [],
            'requirements' => $course->requirements ?? [],
            'audience' => $course->audience ?? [],
            'language' => $course->language === 'id' ? 'Bahasa Indonesia' : strtoupper($course->language),
            'publishedAt' => $course->published_at?->toDateString(),
            'instructorProfile' => [
                'name' => $course->instructor->user->name,
                'headline' => $course->instructor->headline,
                'bio' => $course->instructor->bio,
            ],
            'sections' => $sections,
        ];
    }
}
