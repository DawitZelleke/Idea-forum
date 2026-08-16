<?php

namespace Tests\Feature;

use App\Livewire\Ideas\Show;
use App\Models\Comment;
use App\Models\Idea;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Livewire\Livewire;
use Tests\TestCase;

class CommentVoteTest extends TestCase
{
    use RefreshDatabase;

    public function test_user_can_comment_on_any_idea(): void
    {
        $author = User::factory()->create();
        $commenter = User::factory()->create();
        $idea = Idea::factory()->for($author)->create();

        Livewire::actingAs($commenter)
            ->test(Show::class, ['idea' => $idea])
            ->set('body', 'Great idea, I would use this.')
            ->call('addComment')
            ->assertHasNoErrors();

        $this->assertDatabaseHas('comments', [
            'idea_id' => $idea->id,
            'user_id' => $commenter->id,
        ]);
    }

    public function test_non_owner_cannot_edit_a_comment(): void
    {
        $idea = Idea::factory()->create();
        $comment = Comment::factory()->for($idea)->create();
        $other = User::factory()->create();

        Livewire::actingAs($other)
            ->test(Show::class, ['idea' => $idea])
            ->call('startEditComment', $comment->id)
            ->assertForbidden();
    }

    public function test_vote_toggles_on_and_off(): void
    {
        $user = User::factory()->create();
        $idea = Idea::factory()->create();

        $component = Livewire::actingAs($user)->test(Show::class, ['idea' => $idea]);

        $component->call('toggleVote');
        $this->assertDatabaseHas('votes', ['idea_id' => $idea->id, 'user_id' => $user->id]);

        $component->call('toggleVote');
        $this->assertDatabaseMissing('votes', ['idea_id' => $idea->id, 'user_id' => $user->id]);
    }

    public function test_user_cannot_vote_twice_on_the_same_idea(): void
    {
        $user = User::factory()->create();
        $idea = Idea::factory()->create();

        $idea->votes()->create(['user_id' => $user->id]);

        $this->expectException(\Illuminate\Database\QueryException::class);

        // Bypassing the toggle to prove the DB-level unique constraint holds.
        $idea->votes()->create(['user_id' => $user->id]);
    }
}
