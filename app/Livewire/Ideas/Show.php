<?php

namespace App\Livewire\Ideas;

use App\Models\Comment;
use App\Models\Idea;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use Livewire\Attributes\Layout;
use Livewire\Component;

#[Layout('components.layouts.app')]
class Show extends Component
{
    use AuthorizesRequests;

    public Idea $idea;

    // New comment
    public string $body = '';

    // Editing the idea (owner only)
    public bool $editingIdea = false;

    public string $editTitle = '';

    public string $editDescription = '';

    public string $editCategory = '';

    public string $editStatus = '';

    // Editing a comment
    public ?int $editingCommentId = null;

    public string $editBody = '';

    public function mount(Idea $idea): void
    {
        $this->idea = $idea;
    }

    public function addComment(): void
    {
        $validated = $this->validate([
            'body' => ['required', 'string', 'min:2', 'max:1000'],
        ]);

        $this->idea->comments()->create([
            'user_id' => auth()->id(),
            'body' => $validated['body'],
        ]);

        $this->reset('body');

        session()->flash('status', 'Comment added.');
    }

    public function startEditComment(int $commentId): void
    {
        $comment = Comment::findOrFail($commentId);

        $this->authorize('update', $comment);

        $this->editingCommentId = $comment->id;
        $this->editBody = $comment->body;
        $this->resetErrorBag();
    }

    public function cancelEditComment(): void
    {
        $this->reset('editingCommentId', 'editBody');
        $this->resetErrorBag();
    }

    public function updateComment(): void
    {
        $comment = Comment::findOrFail($this->editingCommentId);

        $this->authorize('update', $comment);

        $validated = $this->validate([
            'editBody' => ['required', 'string', 'min:2', 'max:1000'],
        ]);

        $comment->update(['body' => $validated['editBody']]);

        $this->cancelEditComment();

        session()->flash('status', 'Comment updated.');
    }

    public function deleteComment(int $commentId): void
    {
        $comment = Comment::findOrFail($commentId);

        $this->authorize('delete', $comment);

        $comment->delete();

        session()->flash('status', 'Comment deleted.');
    }

    public function toggleVote(): void
    {
        $userId = auth()->id();

        $existing = $this->idea->votes()->where('user_id', $userId)->first();

        if ($existing) {
            $existing->delete();
        } else {
            $this->idea->votes()->create(['user_id' => $userId]);
        }
    }

    public function startEditIdea(): void
    {
        $this->authorize('update', $this->idea);

        $this->editingIdea = true;
        $this->editTitle = $this->idea->title;
        $this->editDescription = $this->idea->description;
        $this->editCategory = $this->idea->category;
        $this->editStatus = $this->idea->status;
        $this->resetErrorBag();
    }

    public function cancelEditIdea(): void
    {
        $this->reset('editingIdea', 'editTitle', 'editDescription', 'editCategory', 'editStatus');
        $this->resetErrorBag();
    }

    public function updateIdea(): void
    {
        $this->authorize('update', $this->idea);

        $validated = $this->validate([
            'editTitle' => ['required', 'string', 'min:5', 'max:120'],
            'editDescription' => ['required', 'string', 'min:10', 'max:2000'],
            'editCategory' => ['required', 'in:'.implode(',', Idea::CATEGORIES)],
            'editStatus' => ['required', 'in:'.implode(',', Idea::STATUSES)],
        ]);

        $this->idea->update([
            'title' => $validated['editTitle'],
            'description' => $validated['editDescription'],
            'category' => $validated['editCategory'],
            'status' => $validated['editStatus'],
        ]);

        $this->cancelEditIdea();

        session()->flash('status', 'Idea updated.');
    }

    public function deleteIdea(): void
    {
        $this->authorize('delete', $this->idea);

        $this->idea->delete();

        $this->redirectRoute('ideas.index', navigate: true);
    }

    public function render()
    {
        $this->idea->loadCount('votes')->load(['user', 'comments.user']);

        return view('livewire.ideas.show')
            ->title($this->idea->title);
    }
}
