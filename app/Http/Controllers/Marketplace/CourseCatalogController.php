<?php

namespace App\Http\Controllers\Marketplace;

use App\Domain\Catalog\Enums\CourseLevel;
use App\Domain\Catalog\Models\Category;
use App\Domain\Catalog\Models\Course;
use App\Domain\Catalog\Models\Lesson;
use App\Domain\Catalog\Enums\LessonType;
use App\Http\Controllers\Controller;
use App\Http\Requests\Marketplace\CourseCatalogRequest;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Support\Facades\DB;
use App\Domain\Identity\Enums\RoleName;
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
            // A stable tie-breaker prevents duplicates across paginated pages.
            ->orderByDesc('courses.id')
            ->paginate(12)
            ->withQueryString()
            ->through(fn (Course $course): array => $this->courseCard($course));

        return Inertia::render($request->routeIs('home') ? 'marketplace/Landing' : 'marketplace/Catalog', [
            'courses' => $courses,
            'categories' => Category::query()->active()->orderBy('sort_order')->orderBy('name')->get(['name', 'slug', 'image_path'])
                ->map(fn (Category $category): array => [
                    'name' => $category->name,
                    'slug' => $category->slug,
                    'imageUrl' => $category->image_path
                        ? \Illuminate\Support\Facades\Storage::disk('public')
                            ->url($category->image_path)
                        : null,
                ])->values(),
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

    public function show(\Illuminate\Http\Request $request, string $slug): Response
    {
        $course = Course::query()
            ->published()
            ->with([
                'category:id,name,slug',
                'instructor.user:id,name',
                'sections.lessons:id,course_section_id,title,type,content,duration_seconds,is_preview,sort_order',
            ])
            ->where('slug', $slug)
            ->firstOrFail();

        $user = $request->user();
        $isStudent = $user?->hasRole(RoleName::Student) ?? false;
        $enrolled = $isStudent && DB::table('enrollments')
            ->where('student_id', $user->id)
            ->where('course_id', $course->id)
            ->where('status', 'active')
            ->exists();

        return Inertia::render('marketplace/CourseDetail', [
            'course' => $this->courseDetail($course),
            'isAuthenticated' => $user !== null,
            'canEnroll' => $isStudent,
            'enrolled' => $enrolled,
        ]);
    }

    public function join(\Illuminate\Http\Request $request, string $slug): \Illuminate\Http\RedirectResponse
    {
        $course = Course::query()->published()->where('slug', $slug)->firstOrFail();

        if ($request->user() === null) {
            // Store only our own named route as the post-login destination.
            return redirect()->guest(route('login'));
        }

        return to_route('courses.show', $course->slug);
    }

    public function preview(string $slug, Lesson $lesson): Response
    {
        $course = Course::query()->published()->where('slug', $slug)->firstOrFail();
        abort_unless($lesson->section()->where('course_id', $course->id)->exists(), 404);
        abort_unless($lesson->is_preview
            && $lesson->type === LessonType::Article
            && is_string($lesson->content)
            && trim($lesson->content) !== '', 404);

        return Inertia::render('marketplace/LessonPreview', [
            'course' => ['slug' => $course->slug, 'title' => $course->title],
            'lesson' => ['title' => $lesson->title, 'content' => $lesson->content],
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
                    'isPreview' => $lesson->is_preview
                        && $lesson->type === LessonType::Article
                        && is_string($lesson->content)
                        && trim($lesson->content) !== '',
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
