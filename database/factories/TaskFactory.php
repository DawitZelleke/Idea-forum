<?php

namespace Database\Factories;

use App\Models\TaskList;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Task>
 */
class TaskFactory extends Factory
{
    public function definition(): array
    {
        return [
            'task_list_id' => TaskList::factory(),
            'title' => rtrim(fake()->sentence(4), '.'),
            'description' => fake()->boolean(60) ? fake()->sentence(10) : null,
            'due_date' => fake()->boolean(70) ? fake()->dateTimeBetween('now', '+1 month') : null,
            'priority' => fake()->randomElement(['low', 'medium', 'high']),
            'position' => 0,
            'is_completed' => fake()->boolean(25),
        ];
    }
}
