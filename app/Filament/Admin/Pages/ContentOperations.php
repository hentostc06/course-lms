<?php
namespace App\Filament\Admin\Pages;

use App\Domain\Catalog\Enums\CourseStatus;
use App\Domain\Catalog\Models\Category;
use App\Domain\Catalog\Models\Course;
use App\Domain\Instructor\Enums\InstructorStatus;
use App\Domain\Instructor\Models\InstructorProfile;
use Filament\Pages\Page;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Str;
use Illuminate\Validation\Rule;

class ContentOperations extends Page
{
    protected static ?string $slug = 'operasional';
    protected string $view = 'filament.admin.pages.content-operations';

    public string $categoryName = '';
    public string $categoryDescription = '';
    public string $reviewReason = '';
    public ?int $selectedCourse = null;
    public string $notice = '';

    public static function getNavigationLabel(): string { return 'Konten & moderasi'; }
    public function getTitle(): string { return 'Operasional marketplace'; }

    private function ensureAdmin(): void
    {
        abort_unless(auth()->user()?->hasRole('admin') === true, 403);
    }

    public function getCategories() { return Category::query()->orderBy('name')->limit(100)->get(); }
    public function getInstructors() { return InstructorProfile::query()->with('user:id,name,email')->where('status', InstructorStatus::Pending)->oldest()->limit(30)->get(); }
    public function getCourses() { return Course::query()->with(['instructor.user:id,name', 'category:id,name'])->where('status', CourseStatus::PendingReview)->oldest('updated_at')->limit(30)->get(); }

    public function createCategory(): void
    {
        $this->ensureAdmin();
        $data = Validator::make(['name' => $this->categoryName, 'description' => $this->categoryDescription], [
            'name' => ['required','string','min:3','max:120',Rule::unique('categories','name')],
            'description' => ['nullable','string','max:1000'],
        ])->validate();
        Category::query()->create([
            'name' => $data['name'],
            'slug' => Str::slug($data['name']).'-'.Str::lower(Str::random(5)),
            'description' => $data['description'] ?? null,
            'is_active' => true,
        ]);
        $this->reset('categoryName','categoryDescription');
        $this->notice = 'Kategori ditambahkan.';
    }

    public function toggleCategory(int $id): void
    {
        $this->ensureAdmin();
        DB::transaction(function () use ($id) {
            $category = Category::query()->lockForUpdate()->findOrFail($id);
            $category->update(['is_active' => ! $category->is_active]);
        });
        $this->notice = 'Status kategori diperbarui.';
    }

    public function approveInstructor(int $id): void
    {
        $this->ensureAdmin();
        DB::transaction(function () use ($id) {
            $profile = InstructorProfile::query()->lockForUpdate()->findOrFail($id);
            abort_unless($profile->status === InstructorStatus::Pending, 409);
            $profile->update(['status' => InstructorStatus::Approved, 'approved_at' => now()]);
            DB::table('instructor_review_events')->insert([
                'instructor_profile_id' => $profile->id,
                'actor_id' => auth()->id(), 'from_status' => InstructorStatus::Pending->value,
                'to_status' => InstructorStatus::Approved->value, 'created_at' => now(),
            ]);
        });
        $this->notice = 'Instruktur disetujui.';
    }

    public function approveCourse(int $id): void
    {
        $this->ensureAdmin();
        DB::transaction(function () use ($id) {
            $course = Course::query()->lockForUpdate()->findOrFail($id);
            abort_unless($course->status === CourseStatus::PendingReview, 409);
            abort_unless($course->instructor->status === InstructorStatus::Approved, 422, 'Instruktur belum disetujui.');
            $course->forceFill([
                'status' => CourseStatus::Published, 'published_at' => now(),
                'review_feedback' => null, 'reviewed_at' => now(), 'reviewed_by' => auth()->id(),
            ])->save();
            DB::table('course_review_events')->insert([
                'course_id' => $course->id, 'actor_id' => auth()->id(),
                'from_status' => CourseStatus::PendingReview->value,
                'to_status' => CourseStatus::Published->value,
                'note' => null, 'created_at' => now(),
            ]);
        });
        $this->notice = 'Kursus diterbitkan.';
    }

    public function selectRejectedCourse(int $id): void
    {
        $this->ensureAdmin();
        abort_unless(Course::query()->where('status', CourseStatus::PendingReview)->whereKey($id)->exists(), 404);
        $this->selectedCourse = $id;
        $this->reviewReason = '';
    }

    public function rejectCourse(): void
    {
        $this->ensureAdmin();
        $data = Validator::make([
            'id' => $this->selectedCourse, 'reason' => $this->reviewReason,
        ], ['id' => ['required','integer','exists:courses,id'], 'reason' => ['required','string','min:10','max:2000']])->validate();
        DB::transaction(function () use ($data) {
            $course = Course::query()->lockForUpdate()->findOrFail($data['id']);
            abort_unless($course->status === CourseStatus::PendingReview, 409);
            $course->forceFill([
                'status' => CourseStatus::Rejected, 'published_at' => null,
                'review_feedback' => $data['reason'], 'reviewed_at' => now(),
                'reviewed_by' => auth()->id(),
            ])->save();
            DB::table('course_review_events')->insert([
                'course_id' => $course->id, 'actor_id' => auth()->id(),
                'from_status' => CourseStatus::PendingReview->value,
                'to_status' => CourseStatus::Rejected->value,
                'note' => $data['reason'], 'created_at' => now(),
            ]);
        });
        $this->reset('selectedCourse','reviewReason');
        $this->notice = 'Kursus ditolak dengan alasan tercatat.';
    }
}
