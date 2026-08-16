<div>
    <a href="{{ route('ideas.index') }}" class="text-sm text-indigo-600 hover:underline" wire:navigate>
        &larr; Back to forum
    </a>

    {{-- Idea --}}
    <div class="mt-4 rounded-xl bg-white p-6 shadow">
        @if ($editingIdea)
            <form wire:submit="updateIdea" class="space-y-4">
                <div>
                    <label for="editTitle" class="mb-1 block text-sm font-medium">Title</label>
                    <input id="editTitle" type="text" wire:model="editTitle"
                           class="w-full rounded-md border border-slate-300 px-3 py-2 focus:border-indigo-500 focus:outline-none focus:ring-1 focus:ring-indigo-500">
                    @error('editTitle') <p class="mt-1 text-sm text-red-600">{{ $message }}</p> @enderror
                </div>

                <div>
                    <label for="editDescription" class="mb-1 block text-sm font-medium">Description</label>
                    <textarea id="editDescription" wire:model="editDescription" rows="4"
                              class="w-full rounded-md border border-slate-300 px-3 py-2 focus:border-indigo-500 focus:outline-none focus:ring-1 focus:ring-indigo-500"></textarea>
                    @error('editDescription') <p class="mt-1 text-sm text-red-600">{{ $message }}</p> @enderror
                </div>

                <div class="flex flex-wrap gap-4">
                    <div>
                        <label for="editCategory" class="mb-1 block text-sm font-medium">Category</label>
                        <select id="editCategory" wire:model="editCategory"
                                class="rounded-md border border-slate-300 px-3 py-2 focus:border-indigo-500 focus:outline-none">
                            @foreach (\App\Models\Idea::CATEGORIES as $option)
                                <option value="{{ $option }}">{{ $option }}</option>
                            @endforeach
                        </select>
                    </div>

                    <div>
                        <label for="editStatus" class="mb-1 block text-sm font-medium">Status</label>
                        <select id="editStatus" wire:model="editStatus"
                                class="rounded-md border border-slate-300 px-3 py-2 focus:border-indigo-500 focus:outline-none">
                            @foreach (\App\Models\Idea::STATUSES as $option)
                                <option value="{{ $option }}">{{ ucfirst($option) }}</option>
                            @endforeach
                        </select>
                    </div>
                </div>

                <div class="flex gap-2">
                    <button type="submit"
                            class="rounded-md bg-indigo-600 px-4 py-2 text-sm font-medium text-white hover:bg-indigo-500">
                        Save
                    </button>
                    <button type="button" wire:click="cancelEditIdea"
                            class="rounded-md border border-slate-300 px-4 py-2 text-sm font-medium hover:bg-slate-50">
                        Cancel
                    </button>
                </div>
            </form>
        @else
            <div class="flex gap-4">
                <button wire:click="toggleVote"
                        class="flex h-16 w-14 shrink-0 flex-col items-center justify-center rounded-lg border text-base font-semibold
                               {{ $idea->isVotedBy(auth()->user())
                                   ? 'border-indigo-600 bg-indigo-600 text-white'
                                   : 'border-slate-300 text-slate-600 hover:border-indigo-400 hover:text-indigo-600' }}"
                        aria-label="Toggle vote">
                    <span aria-hidden="true">&#9650;</span>
                    {{ $idea->votes_count }}
                </button>

                <div class="min-w-0 flex-1">
                    <div class="flex flex-wrap items-center gap-2">
                        <h1 class="text-xl font-bold text-slate-900">{{ $idea->title }}</h1>
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

                    <p class="mt-1 text-xs text-slate-400">
                        by {{ $idea->user->name }} &middot; {{ $idea->created_at->diffForHumans() }}
                    </p>

                    <p class="mt-3 whitespace-pre-line text-slate-700">{{ $idea->description }}</p>

                    @can('update', $idea)
                        <div class="mt-4 flex gap-3 text-sm">
                            <button wire:click="startEditIdea" class="text-indigo-600 hover:underline">Edit</button>
                            <button wire:click="deleteIdea"
                                    wire:confirm="Delete this idea and all its comments?"
                                    class="text-red-500 hover:underline">
                                Delete
                            </button>
                        </div>
                    @endcan
                </div>
            </div>
        @endif
    </div>

    {{-- Comments --}}
    <h2 class="mt-8 mb-4 text-lg font-semibold">
        {{ $idea->comments->count() }} {{ Str::plural('Comment', $idea->comments->count()) }}
    </h2>

    <form wire:submit="addComment" class="mb-6 rounded-xl bg-white p-4 shadow">
        <label for="body" class="sr-only">Add a comment</label>
        <textarea id="body" wire:model="body" rows="2" placeholder="Add a comment..."
                  class="w-full rounded-md border border-slate-300 px-3 py-2 focus:border-indigo-500 focus:outline-none focus:ring-1 focus:ring-indigo-500"></textarea>
        @error('body') <p class="mt-1 text-sm text-red-600">{{ $message }}</p> @enderror
        <button type="submit"
                class="mt-2 rounded-md bg-indigo-600 px-4 py-1.5 text-sm font-medium text-white hover:bg-indigo-500">
            Comment
        </button>
    </form>

    <div class="space-y-3">
        @forelse ($idea->comments as $comment)
            <div class="rounded-xl bg-white p-4 shadow" wire:key="comment-{{ $comment->id }}">
                @if ($editingCommentId === $comment->id)
                    <form wire:submit="updateComment">
                        <textarea wire:model="editBody" rows="2"
                                  class="w-full rounded-md border border-slate-300 px-3 py-2 focus:border-indigo-500 focus:outline-none focus:ring-1 focus:ring-indigo-500"></textarea>
                        @error('editBody') <p class="mt-1 text-sm text-red-600">{{ $message }}</p> @enderror
                        <div class="mt-2 flex gap-2">
                            <button type="submit"
                                    class="rounded-md bg-indigo-600 px-3 py-1 text-sm font-medium text-white hover:bg-indigo-500">
                                Save
                            </button>
                            <button type="button" wire:click="cancelEditComment"
                                    class="rounded-md border border-slate-300 px-3 py-1 text-sm hover:bg-slate-50">
                                Cancel
                            </button>
                        </div>
                    </form>
                @else
                    <p class="text-sm text-slate-700 whitespace-pre-line">{{ $comment->body }}</p>
                    <div class="mt-2 flex flex-wrap items-center gap-3 text-xs text-slate-400">
                        <span class="font-medium text-slate-500">{{ $comment->user->name }}</span>
                        <span>{{ $comment->created_at->diffForHumans() }}</span>

                        @can('update', $comment)
                            <button wire:click="startEditComment({{ $comment->id }})"
                                    class="text-indigo-600 hover:underline">Edit</button>
                        @endcan
                        @can('delete', $comment)
                            <button wire:click="deleteComment({{ $comment->id }})"
                                    wire:confirm="Delete this comment?"
                                    class="text-red-500 hover:underline">Delete</button>
                        @endcan
                    </div>
                @endif
            </div>
        @empty
            <p class="text-sm text-slate-500">No comments yet. Start the discussion!</p>
        @endforelse
    </div>
</div>
