<?php

namespace Database\Factories;

use App\Models\Idea;
use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends Factory<Idea>
 */
class IdeaFactory extends Factory
{
    public function definition(): array
    {
        return [
            'user_id' => User::factory(),
            'title' => rtrim($this->faker->sentence(6), '.'),
            'description' => $this->faker->paragraph(3),
            'category' => $this->faker->randomElement(Idea::CATEGORIES),
            'status' => 'open',
        ];
    }
}
