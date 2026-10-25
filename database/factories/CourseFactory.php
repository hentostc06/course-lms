<?php

namespace Database\Factories;

use App\Domain\Catalog\Enums\CourseLevel;
use App\Domain\Catalog\Enums\CourseStatus;
use App\Domain\Catalog\Models\Category;
use App\Domain\Catalog\Models\Course;
use App\Domain\Instructor\Models\InstructorProfile;
use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Str;

/** @extends Factory<Course> */
class CourseFactory extends Factory
{
    protected $model = Course::class;

    /** @return array<string, mixed> */
    public function definition(): array
    {
        $title = fake()->unique()->sentence(5);

        return [
            'instructor_id' => InstructorProfile::factory(),
            'category_id' => Category::factory(),
            'title' => $title,
            'slug' => Str::slug($title).'-'.fake()->unique()->numberBetween(100, 999),
            'summary' => fake()->sentence(16),
            'description' => fake()->paragraphs(2, true),
            'outcomes' => [
                'Menerapkan konsep utama melalui latihan terarah.',
                'Mengevaluasi hasil kerja dengan kriteria yang jelas.',
            ],
            'requirements' => ['Perangkat dengan koneksi internet.'],
            'audience' => ['Profesional yang ingin memperkuat keterampilan praktis.'],
            'price_idr' => fake()->randomElement([0, 199000, 349000]),
            'level' => CourseLevel::Beginner,
            'language' => 'id',
            'status' => CourseStatus::Draft,
            'thumbnail_url' => null,
            'total_duration_minutes' => 120,
            'published_at' => null,
        ];
    }

    public function published(): static
    {
        return $this->state(fn (): array => [
            'status' => CourseStatus::Published,
            'published_at' => now()->subMinute(),
        ]);
    }
}
