<?php

namespace Database\Factories;

use App\Domain\Instructor\Enums\InstructorStatus;
use App\Domain\Instructor\Models\InstructorProfile;
use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Str;

/** @extends Factory<InstructorProfile> */
class InstructorProfileFactory extends Factory
{
    protected $model = InstructorProfile::class;

    /** @return array<string, mixed> */
    public function definition(): array
    {
        $name = fake()->unique()->name();

        return [
            'user_id' => User::factory(),
            'slug' => Str::slug($name).'-'.fake()->unique()->numberBetween(100, 999),
            'headline' => 'Praktisi dan fasilitator pelatihan profesional',
            'bio' => fake()->paragraph(),
            'status' => InstructorStatus::Approved,
            'approved_at' => now(),
        ];
    }

    public function pending(): static
    {
        return $this->state(fn (): array => [
            'status' => InstructorStatus::Pending,
            'approved_at' => null,
        ]);
    }
}
