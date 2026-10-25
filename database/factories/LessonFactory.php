<?php

namespace Database\Factories;

use App\Domain\Catalog\Enums\LessonType;
use App\Domain\Catalog\Models\CourseSection;
use App\Domain\Catalog\Models\Lesson;
use Illuminate\Database\Eloquent\Factories\Factory;

/** @extends Factory<Lesson> */
class LessonFactory extends Factory
{
    protected $model = Lesson::class;

    /** @return array<string, mixed> */
    public function definition(): array
    {
        return [
            'course_section_id' => CourseSection::factory(),
            'title' => fake()->sentence(4),
            'summary' => fake()->sentence(),
            'type' => LessonType::Video,
            'content' => null,
            'duration_seconds' => 600,
            'is_preview' => false,
            'sort_order' => 1,
        ];
    }
}
