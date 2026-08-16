<?php

namespace Database\Seeders;

use App\Models\Comment;
use App\Models\Idea;
use App\Models\User;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database with demo accounts and forum content.
     *
     * Demo login: demo@ideanest.test / password
     */
    public function run(): void
    {
        $demo = User::factory()->create([
            'name' => 'Demo User',
            'email' => 'demo@ideanest.test',
        ]);

        $members = User::factory()->count(3)->sequence(
            ['name' => 'Aisha Rahman', 'email' => 'aisha@ideanest.test'],
            ['name' => 'Marc Tremblay', 'email' => 'marc@ideanest.test'],
            ['name' => 'Priya Patel', 'email' => 'priya@ideanest.test'],
        )->create();

        $everyone = $members->push($demo);

        // Ideas from the demo user and other members.
        $ideas = collect([
            Idea::factory()->for($demo)->create([
                'title' => 'Dark mode for the student portal',
                'category' => 'Feature',
            ]),
            Idea::factory()->for($members[0])->create([
                'title' => 'Weekly study group for web dev courses',
                'category' => 'Discussion',
            ]),
            Idea::factory()->for($members[1])->create([
                'title' => 'Improve search on the course catalog',
                'category' => 'Improvement',
                'status' => 'planned',
            ]),
        ])->concat(
            Idea::factory()
                ->count(6)
                ->recycle($everyone)
                ->create()
        );

        // Comments and votes spread across users and ideas.
        foreach ($ideas as $idea) {
            Comment::factory()
                ->count(random_int(0, 3))
                ->for($idea)
                ->recycle($everyone)
                ->create();

            $voters = $everyone->random(random_int(0, $everyone->count()));

            foreach ($voters as $voter) {
                $idea->votes()->create(['user_id' => $voter->id]);
            }
        }
    }
}
