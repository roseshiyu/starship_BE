<?php

namespace Database\Factories;

use App\Enums\Course\Status;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Domain>
 */
class CourseFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'name' => fake()->text(20),
            'description' => fake()->text(),
            'code' => strtoupper(fake()->unique()->text(10)),
            'weeks' => 5,
            'credits' => 1,
            'status' => Status::active,
        ];
    }
}
