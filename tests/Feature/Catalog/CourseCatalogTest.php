<?php

namespace Tests\Feature\Catalog;

use App\Domain\Catalog\Enums\CourseLevel;
use App\Domain\Catalog\Models\Category;
use App\Domain\Catalog\Models\Course;
use App\Domain\Catalog\Models\CourseSection;
use App\Domain\Catalog\Models\Lesson;
use App\Domain\Instructor\Enums\InstructorStatus;
use App\Domain\Instructor\Models\InstructorProfile;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Inertia\Testing\AssertableInertia as Assert;
use Tests\TestCase;

class CourseCatalogTest extends TestCase
{
    use RefreshDatabase;

    public function test_catalog_only_lists_published_courses(): void
    {
        $published = Course::factory()->published()->create(['title' => 'Audit Internal Berbasis Risiko']);
        Course::factory()->create(['title' => 'Draf Rahasia Instruktur']);
        Course::factory()->published()->create([
            'title' => 'Belum Tayang',
            'published_at' => now()->addDay(),
        ]);
        $suspendedInstructor = InstructorProfile::factory()->create([
            'status' => InstructorStatus::Suspended,
        ]);
        Course::factory()->published()->for($suspendedInstructor, 'instructor')->create([
            'title' => 'Kursus Instruktur Nonaktif',
        ]);

        $this->get(route('courses.index'))
            ->assertOk()
            ->assertInertia(fn (Assert $page) => $page
                ->component('marketplace/Catalog')
                ->has('courses.data', 1)
                ->where('courses.data.0.slug', $published->slug)
                ->where('courses.total', 1));
    }

    public function test_catalog_filters_search_category_level_and_price(): void
    {
        $targetCategory = Category::factory()->create(['slug' => 'audit']);
        $otherCategory = Category::factory()->create(['slug' => 'kepemimpinan']);

        $target = Course::factory()->published()->for($targetCategory)->create([
            'title' => 'Dasar Audit Operasional',
            'level' => CourseLevel::Intermediate,
            'price_idr' => 249000,
        ]);
        Course::factory()->published()->for($otherCategory)->create([
            'title' => 'Dasar Kepemimpinan Tim',
            'level' => CourseLevel::Intermediate,
            'price_idr' => 249000,
        ]);

        $this->get(route('courses.index', [
            'q' => 'Audit',
            'category' => 'audit',
            'level' => CourseLevel::Intermediate->value,
            'price' => 'paid',
        ]))->assertOk()->assertInertia(fn (Assert $page) => $page
            ->has('courses.data', 1)
            ->where('courses.data.0.slug', $target->slug));
    }

    public function test_published_course_detail_exposes_curriculum_without_lesson_content(): void
    {
        $course = Course::factory()->published()->create(['title' => 'Manajemen Risiko Praktis']);
        $section = CourseSection::factory()->for($course)->create(['title' => 'Fondasi Risiko']);
        Lesson::factory()->for($section, 'section')->create([
            'title' => 'Menyusun Konteks Risiko',
            'content' => 'Materi privat tidak boleh masuk payload katalog.',
            'is_preview' => false,
        ]);

        $this->get(route('courses.show', $course->slug))
            ->assertOk()
            ->assertInertia(fn (Assert $page) => $page
                ->component('marketplace/CourseDetail')
                ->where('course.title', 'Manajemen Risiko Praktis')
                ->where('course.sections.0.title', 'Fondasi Risiko')
                ->where('course.sections.0.lessons.0.title', 'Menyusun Konteks Risiko')
                ->missing('course.sections.0.lessons.0.content'));
    }

    public function test_deactivated_category_hides_course_from_public_catalog(): void
    {
        $category = Category::factory()->create(['is_active' => false]);
        $course = Course::factory()->published()->for($category)->create();
        $this->get(route('courses.index'))->assertInertia(fn (Assert $page) => $page
            ->where('courses.total', 0));
        $this->get(route('courses.show', $course->slug))->assertNotFound();
    }

    public function test_preview_shows_only_explicitly_previewable_articles(): void
    {
        $course = Course::factory()->published()->create();
        $section = CourseSection::factory()->for($course)->create();
        $preview = Lesson::factory()->for($section, 'section')->create([
            'type' => 'article', 'content' => 'Contoh artikel yang dapat dibaca publik.',
            'is_preview' => true,
        ]);
        $this->get(route('courses.preview', [$course->slug, $preview]))
            ->assertOk()->assertInertia(fn (Assert $page) => $page
                ->component('marketplace/LessonPreview')
                ->where('lesson.content', 'Contoh artikel yang dapat dibaca publik.'));
        $preview->update(['is_preview' => false]);
        $this->get(route('courses.preview', [$course->slug, $preview]))->assertNotFound();
    }

    public function test_unpublished_course_detail_returns_not_found(): void
    {
        $course = Course::factory()->create();

        $this->get(route('courses.show', $course->slug))->assertNotFound();
    }
}
