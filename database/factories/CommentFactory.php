<?php

namespace Database\Factories;

use App\Models\Comment;
use App\Models\Idea;
use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends Factory<Comment>
 */
class CommentFactory extends Factory
{
    public function definition(): array
    {
        return [
            'idea_id' => Idea::factory(),
            'user_id' => User::factory(),
            'body' => $this->faker->sentences(2, asText: true),
        ];
    }
}
