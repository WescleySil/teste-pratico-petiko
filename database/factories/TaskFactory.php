<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;


class TaskFactory extends Factory
{
    public function definition(): array
    {
        return [
            'title' => $this->faker->sentence,
            'description' => $this->faker->paragraph,
            'status' => $this->faker->randomElement(['pending', 'overdue', 'completed']),
            'due_date' => now()->addWeeks(rand(1, 5)),
        ];
    }
}
