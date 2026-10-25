<?php

namespace Database\Factories;

use App\Domain\Catalog\Models\Course;
use App\Domain\Catalog\Models\CourseSection;
use Illuminate\Database\Eloquent\Factories\Factory;

/** @extends Factory<CourseSection> */
class CourseSectionFactory extends Factory
{
    protected $model = CourseSection::class;

    /** @return array<string, mixed> */
    public function definition(): array
    {
        return [
            'course_id' => Course::factory(),
            'title' => fake()->sentence(3),
            'description' => fake()->sentence(),
            'sort_order' => 1,
        ];
    }
}
