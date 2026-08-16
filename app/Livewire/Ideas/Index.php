<?php

namespace App\Livewire\Ideas;

use App\Models\Idea;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use Livewire\Attributes\Layout;
use Livewire\Attributes\Title;
use Livewire\Attributes\Url;
use Livewire\Component;
use Livewire\WithPagination;

#[Layout('components.layouts.app')]
#[Title('Idea Forum')]
class Index extends Component
{
    use AuthorizesRequests, WithPagination;

    // New-idea form
    public bool $showForm = false;

    public string $title = '';

    public string $description = '';

    public string $category = 'Discussion';

    // Filters (kept in the query string so links are shareable)
    #[Url]
    public string $filterCategory = '';

    #[Url]
    public string $sort = 'newest'; // newest | top

    protected function rules(): array
    {
        return [
            'title' => ['required', 'string', 'min:5', 'max:120'],
            'description' => ['required', 'string', 'min:10', 'max:2000'],
            'category' => ['required', 'in:'.implode(',', Idea::CATEGORIES)],
        ];
    }

    public function createIdea(): void
    {
        $validated = $this->validate();

        auth()->user()->ideas()->create($validated);

        $this->reset('title', 'description', 'showForm');
        $this->category = 'Discussion';
        $this->resetPage();

        session()->flash('status', 'Idea posted.');
    }

    public function deleteIdea(int $ideaId): void
    {
        $idea = Idea::findOrFail($ideaId);

        $this->authorize('delete', $idea);

        $idea->delete();

        session()->flash('status', 'Idea deleted.');
    }

    public function toggleVote(int $ideaId): void
    {
        $idea = Idea::findOrFail($ideaId);
        $userId = auth()->id();

        $existing = $idea->votes()->where('user_id', $userId)->first();

        if ($existing) {
            $existing->delete();
        } else {
            $idea->votes()->create(['user_id' => $userId]);
        }
    }

    public function updatedFilterCategory(): void
    {
        $this->resetPage();
    }

    public function updatedSort(): void
    {
        $this->resetPage();
    }

    public function render()
    {
        $ideas = Idea::query()
            ->with('user')
            ->withCount(['votes', 'comments'])
            ->withExists(['votes as voted_by_user' => fn ($query) => $query->where('user_id', auth()->id())])
            ->when($this->filterCategory !== '', fn ($query) => $query->where('category', $this->filterCategory))
            ->when(
                $this->sort === 'top',
                fn ($query) => $query->orderByDesc('votes_count')->latest(),
                fn ($query) => $query->latest(),
            )
            ->paginate(8);

        return view('livewire.ideas.index', [
            'ideas' => $ideas,
            'categories' => Idea::CATEGORIES,
        ]);
    }
}
