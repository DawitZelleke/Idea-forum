<?php

namespace Database\Seeders;

use App\Models\Task;
use App\Models\TaskList;
use App\Models\User;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database with a demo account and sample data.
     *
     * Demo login: demo@tasknest.test / password
     */
    public function run(): void
    {
        $demo = User::factory()->create([
            'name' => 'Demo User',
            'email' => 'demo@tasknest.test',
        ]);

        $lists = [
            ['name' => 'School', 'color' => '#6366f1'],
            ['name' => 'Job Search', 'color' => '#10b981'],
            ['name' => 'Personal', 'color' => '#f59e0b'],
        ];

        foreach ($lists as $data) {
            $list = TaskList::factory()->for($demo)->create($data);

            Task::factory()
                ->count(5)
                ->for($list, 'taskList')
                ->sequence(fn ($sequence) => ['position' => $sequence->index + 1])
                ->create();
        }

        // A second user proves data isolation between accounts.
        $other = User::factory()->create([
            'name' => 'Other User',
            'email' => 'other@tasknest.test',
        ]);

        TaskList::factory()
            ->for($other)
            ->has(Task::factory()->count(3)->sequence(
                ['position' => 1], ['position' => 2], ['position' => 3],
            ), 'tasks')
            ->create(['name' => 'Other Errands', 'color' => '#ef4444']);
    }
}
