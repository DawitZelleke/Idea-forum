<div>
    <div class="mb-6 flex flex-wrap items-center justify-between gap-4">
        <h1 class="text-2xl font-bold">Idea Forum</h1>
        <button wire:click="$toggle('showForm')"
                class="rounded-md bg-indigo-600 px-4 py-2 text-sm font-medium text-white hover:bg-indigo-500">
            {{ $showForm ? 'Close' : '+ New idea' }}
        </button>
    </div>

    {{-- New idea form --}}
    @if ($showForm)
        <form wire:submit="createIdea" class="mb-8 space-y-4 rounded-xl bg-white p-6 shadow">
            <div>
                <label for="title" class="mb-1 block text-sm font-medium">Title</label>
                <input id="title" type="text" wire:model="title" placeholder="Pitch it in one line"
                       class="w-full rounded-md border border-slate-300 px-3 py-2 focus:border-indigo-500 focus:outline-none focus:ring-1 focus:ring-indigo-500">
                @error('title') <p class="mt-1 text-sm text-red-600">{{ $message }}</p> @enderror
            </div>

            <div>
                <label for="description" class="mb-1 block text-sm font-medium">Description</label>
                <textarea id="description" wire:model="description" rows="4"
                          placeholder="Explain the idea, the problem it solves, and why it matters."
                          class="w-full rounded-md border border-slate-300 px-3 py-2 focus:border-indigo-500 focus:outline-none focus:ring-1 focus:ring-indigo-500"></textarea>
                @error('description') <p class="mt-1 text-sm text-red-600">{{ $message }}</p> @enderror
            </div>

            <div>
                <label for="category" class="mb-1 block text-sm font-medium">Category</label>
                <select id="category" wire:model="category"
                        class="rounded-md border border-slate-300 px-3 py-2 focus:border-indigo-500 focus:outline-none focus:ring-1 focus:ring-indigo-500">
                    @foreach ($categories as $option)
                        <option value="{{ $option }}">{{ $option }}</option>
                    @endforeach
                </select>
                @error('category') <p class="mt-1 text-sm text-red-600">{{ $message }}</p> @enderror
            </div>

            <button type="submit"
                    class="rounded-md bg-indigo-600 px-4 py-2 text-sm font-medium text-white hover:bg-indigo-500">
                Post idea
            </button>
        </form>
    @endif

    {{-- Filters --}}
    <div class="mb-6 flex flex-wrap items-center gap-3 text-sm">
        <select wire:model.live="filterCategory"
                class="rounded-md border border-slate-300 px-3 py-1.5 focus:border-indigo-500 focus:outline-none">
            <option value="">All categories</option>
            @foreach ($categories as $option)
                <option value="{{ $option }}">{{ $option }}</option>
            @endforeach
        </select>

        <select wire:model.live="sort"
                class="rounded-md border border-slate-300 px-3 py-1.5 focus:border-indigo-500 focus:outline-none">
            <option value="newest">Newest first</option>
            <option value="top">Most voted</option>
        </select>
    </div>

    {{-- Idea cards --}}
    <div class="space-y-4">
        @forelse ($ideas as $idea)
            <div class="flex gap-4 rounded-xl bg-white p-5 shadow" wire:key="idea-{{ $idea->id }}">
                {{-- Vote button --}}
                <button wire:click="toggleVote({{ $idea->id }})"
                        class="flex h-14 w-12 shrink-0 flex-col items-center justify-center rounded-lg border text-sm font-semibold
                               {{ $idea->isVotedBy(auth()->user())
                                   ? 'border-indigo-600 bg-indigo-600 text-white'
                                   : 'border-slate-300 text-slate-600 hover:border-indigo-400 hover:text-indigo-600' }}"
                        aria-label="Toggle vote">
                    <span aria-hidden="true">&#9650;</span>
                    {{ $idea->votes_count }}
                </button>

                <div class="min-w-0 flex-1">
                    <div class="flex flex-wrap items-center gap-2">
                        <a href="{{ route('ideas.show', $idea) }}"
                           class="font-semibold text-slate-900 hover:text-indigo-600" wire:navigate>
                            {{ $idea->title }}
                        </a>
                        <span class="rounded-full bg-indigo-100 px-2 py-0.5 text-xs font-medium text-indigo-700">
                            {{ $idea->category }}
                        </span>
                        @if ($idea->status !== 'open')
                            <span class="rounded-full px-2 py-0.5 text-xs font-medium
                                         {{ $idea->status === 'completed' ? 'bg-emerald-100 text-emerald-700' : 'bg-amber-100 text-amber-700' }}">
                                {{ ucfirst($idea->status) }}
                            </span>
                        @endif
                    </div>

                    <p class="mt-1 line-clamp-2 text-sm text-slate-600">{{ $idea->description }}</p>

                    <div class="mt-2 flex flex-wrap items-center gap-3 text-xs text-slate-400">
                        <span>by {{ $idea->user->name }}</span>
                        <span>{{ $idea->created_at->diffForHumans() }}</span>
                        <a href="{{ route('ideas.show', $idea) }}" class="hover:text-indigo-600" wire:navigate>
                            {{ $idea->comments_count }} {{ Str::plural('comment', $idea->comments_count) }}
                        </a>

                        @can('delete', $idea)
                            <button wire:click="deleteIdea({{ $idea->id }})"
                                    wire:confirm="Delete this idea and all its comments?"
                                    class="text-red-500 hover:text-red-600">
                                Delete
                            </button>
                        @endcan
                    </div>
                </div>
            </div>
        @empty
            <div class="rounded-xl border border-dashed border-slate-300 p-10 text-center text-slate-500">
                No ideas yet{{ $filterCategory ? ' in this category' : '' }}. Be the first to post one!
            </div>
        @endforelse
    </div>

    <div class="mt-6">
        {{ $ideas->links() }}
    </div>
</div>
