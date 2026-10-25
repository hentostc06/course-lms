<?php

namespace App\Http\Controllers\Instructor;

use App\Domain\Catalog\Enums\CourseLevel;
use App\Domain\Catalog\Enums\CourseStatus;
use App\Domain\Catalog\Models\Category;
use App\Domain\Catalog\Models\Course;
use App\Domain\Instructor\Enums\InstructorStatus;
use App\Http\Controllers\Controller;
use App\Http\Requests\Instructor\CourseDetailsRequest;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;
use Inertia\Inertia;
use Inertia\Response;

class InstructorCourseController extends Controller
{
    public function index(Request $request): Response
    {
        $profile = $request->user()->instructorProfile;

        return Inertia::render('instructor/Courses', [
            'isApproved' => $profile?->status === InstructorStatus::Approved,
            'courses' => $profile === null ? [] : $profile->courses()
                ->latest('updated_at')->get(['id', 'slug', 'title', 'status', 'price_idr', 'updated_at'])
                ->map(fn (Course $course) => [
                    'slug' => $course->slug,
                    'title' => $course->title,
                    'status' => $course->status->value,
                    'priceIdr' => $course->price_idr,
                    'updatedAt' => $course->updated_at?->toDateString(),
                ])->all(),
        ]);
    }

    public function create(Request $request): Response
    {
        abort_unless($request->user()->can('create', Course::class)
            && $request->user()->instructorProfile?->status === InstructorStatus::Approved, 403);

        return Inertia::render('instructor/CourseEditor', [
            'course' => null,
            ...$this->formOptions(),
        ]);
    }

    public function store(CourseDetailsRequest $request): RedirectResponse
    {
        abort_unless($request->user()->can('create', Course::class)
            && $request->user()->instructorProfile?->status === InstructorStatus::Approved, 403);

        $data = $request->validated();
        $data['instructor_id'] = $request->user()->instructorProfile->id;
        $data['status'] = CourseStatus::Draft;
        $data['slug'] = Str::slug($data['title']).'-'.Str::lower(Str::random(8));
        $course = Course::query()->create($data);

        return to_route('instructor.courses.edit', $course)->with('success', 'Draf kursus berhasil dibuat.');
    }

    public function edit(Request $request, Course $course): Response
    {
        abort_unless($request->user()->can('update', $course), 403);

        return Inertia::render('instructor/CourseEditor', [
            'course' => $this->coursePayload($course),
            ...$this->formOptions(),
        ]);
    }

    public function update(CourseDetailsRequest $request, Course $course): RedirectResponse
    {
        abort_unless($request->user()->can('update', $course), 403);
        DB::transaction(function () use ($request, $course): void {
            $locked = Course::query()->lockForUpdate()->findOrFail($course->id);
            abort_unless($request->user()->can('update', $locked), 403);
            // Status, slug, ownership and published_at are never accepted from browser input.
            $locked->update($request->validated());
        });

        return to_route('instructor.courses.edit', $course)->with('success', 'Draf diperbarui.');
    }

    /** @return array<string, mixed> */
    private function formOptions(): array
    {
        return [
            'categories' => Category::query()->active()->orderBy('name')->get(['id', 'name']),
            'levels' => array_map(
                fn (CourseLevel $level) => ['value' => $level->value, 'label' => $level->label()],
                CourseLevel::cases(),
            ),
        ];
    }

    /** @return array<string, mixed> */
    private function coursePayload(Course $course): array
    {
        return [
            'slug' => $course->slug,
            'title' => $course->title,
            'category_id' => $course->category_id,
            'summary' => $course->summary,
            'description' => $course->description,
            'price_idr' => $course->price_idr,
            'level' => $course->level->value,
            'language' => $course->language,
            'outcomes' => $course->outcomes,
            'requirements' => $course->requirements ?? [],
            'audience' => $course->audience ?? [],
            'thumbnail_url' => $course->thumbnail_url,
            'status' => $course->status->value,
            'review_feedback' => $course->review_feedback,
        ];
    }
}
