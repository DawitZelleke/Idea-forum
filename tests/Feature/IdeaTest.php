<?php

namespace Tests\Feature;

use App\Livewire\Ideas\Index;
use App\Livewire\Ideas\Show;
use App\Models\Idea;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Livewire\Livewire;
use Tests\TestCase;

class IdeaTest extends TestCase
{
    use RefreshDatabase;

    public function test_user_can_create_an_idea(): void
    {
        $user = User::factory()->create();

        Livewire::actingAs($user)
            ->test(Index::class)
            ->set('title', 'Add a campus carpool board')
            ->set('description', 'A board where students can offer or request rides to campus.')
            ->set('category', 'Feature')
            ->call('createIdea')
            ->assertHasNoErrors();

        $this->assertDatabaseHas('ideas', [
            'user_id' => $user->id,
            'title' => 'Add a campus carpool board',
        ]);
    }

    public function test_idea_creation_is_validated(): void
    {
        $user = User::factory()->create();

        Livewire::actingAs($user)
            ->test(Index::class)
            ->set('title', 'abc') // too short
            ->set('description', 'short') // too short
            ->set('category', 'NotACategory')
            ->call('createIdea')
            ->assertHasErrors(['title', 'description', 'category']);
    }

    public function test_all_users_can_see_each_others_ideas(): void
    {
        $author = User::factory()->create();
        $viewer = User::factory()->create();
        $idea = Idea::factory()->for($author)->create();

        Livewire::actingAs($viewer)
            ->test(Index::class)
            ->assertSee($idea->title);
    }

    public function test_owner_can_update_their_idea(): void
    {
        $user = User::factory()->create();
        $idea = Idea::factory()->for($user)->create();

        Livewire::actingAs($user)
            ->test(Show::class, ['idea' => $idea])
            ->call('startEditIdea')
            ->set('editTitle', 'Updated idea title')
            ->set('editDescription', 'This description was updated by the owner.')
            ->set('editCategory', 'Improvement')
            ->set('editStatus', 'planned')
            ->call('updateIdea')
            ->assertHasNoErrors();

        $this->assertDatabaseHas('ideas', [
            'id' => $idea->id,
            'title' => 'Updated idea title',
            'status' => 'planned',
        ]);
    }

    public function test_non_owner_cannot_update_an_idea(): void
    {
        $author = User::factory()->create();
        $other = User::factory()->create();
        $idea = Idea::factory()->for($author)->create();

        Livewire::actingAs($other)
            ->test(Show::class, ['idea' => $idea])
            ->call('startEditIdea')
            ->assertForbidden();
    }

    public function test_non_owner_cannot_delete_an_idea(): void
    {
        $author = User::factory()->create();
        $other = User::factory()->create();
        $idea = Idea::factory()->for($author)->create();

        Livewire::actingAs($other)
            ->test(Show::class, ['idea' => $idea])
            ->call('deleteIdea')
            ->assertForbidden();

        $this->assertDatabaseHas('ideas', ['id' => $idea->id]);
    }

    public function test_owner_can_delete_their_idea(): void
    {
        $user = User::factory()->create();
        $idea = Idea::factory()->for($user)->create();

        Livewire::actingAs($user)
            ->test(Show::class, ['idea' => $idea])
            ->call('deleteIdea')
            ->assertRedirect(route('ideas.index'));

        $this->assertDatabaseMissing('ideas', ['id' => $idea->id]);
    }
}
