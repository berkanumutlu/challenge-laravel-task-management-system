<?php

namespace Database\Factories;

use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Task>
 */
class TaskFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'user_id'     => User::factory(),
            'title'       => fake()->sentence(6),
            'description' => fake()->paragraph(3),
            'status'      => fake()->randomElement(['pending', 'in_progress', 'completed']),
            'created_at'  => date('Y-m-d H:i:s')
        ];
    }
}
