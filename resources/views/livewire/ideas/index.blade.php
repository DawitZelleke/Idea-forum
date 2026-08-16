<div style="max-width: 1024px; margin: 0 auto; padding: 40px 24px;">
    
    {{-- Hero Section / Header --}}
    <div style="background: linear-gradient(135deg, #0f172a 0%, #1e1b4b 100%); border-radius: 24px; padding: 40px; box-shadow: 0 20px 25px -5px rgba(0, 0, 0, 0.1); display: flex; align-items: center; justify-content: space-between; gap: 32px; margin-bottom: 36px;">
        <div style="flex: 1;">
            <span style="display: inline-block; background: rgba(99, 102, 241, 0.25); color: #818cf8; font-size: 11px; font-weight: 700; padding: 6px 14px; border-radius: 9999px; text-transform: uppercase; letter-spacing: 0.05em; border: 1px solid rgba(99, 102, 241, 0.4); margin-bottom: 12px;">Community Workspace</span>
            <h1 style="color: #ffffff; font-size: 32px; font-weight: 900; margin: 0 0 8px 0; letter-spacing: -0.025em;">Idea Forum</h1>
            <p style="color: #cbd5e1; font-size: 15px; margin: 0; line-height: 1.5;">Share your thoughts, propose new features, and vote on what we should build next.</p>
        </div>
        
        {{-- Compact Pill Button --}}
        <button wire:click="$toggle('showForm')"
                style="background: #4f46e5; color: #ffffff; border: none; border-radius: 9999px; padding: 14px 28px; font-size: 14px; font-weight: 700; display: inline-flex; align-items: center; gap: 10px; cursor: pointer; box-shadow: 0 10px 15px -3px rgba(79, 70, 229, 0.4); white-space: nowrap; transition: all 0.2s ease;">
            <span style="display: flex; align-items: center; justify-content: center; width: 22px; height: 22px; border-radius: 50%; background: rgba(255,255,255,0.2); font-size: 14px;">+</span>
            <span>{{ $showForm ? 'Close Form' : 'New Idea' }}</span>
        </button>
    </div>

    {{-- New Idea Form Card --}}
    @if ($showForm)
        <div style="background: #ffffff; border: 1px solid #e2e8f0; border-radius: 24px; padding: 36px; box-shadow: 0 20px 25px -5px rgba(0, 0, 0, 0.05); margin-bottom: 36px;">
            <div style="display: flex; align-items: center; justify-content: space-between; margin-bottom: 24px; padding-bottom: 16px; border-bottom: 1px solid #f1f5f9;">
                <h2 style="color: #0f172a; font-size: 20px; font-weight: 800; margin: 0;">Got a brilliant idea?</h2>
                <button wire:click="$toggle('showForm')" style="background: none; border: none; color: #94a3b8; font-size: 14px; font-weight: 600; cursor: pointer;">✕ Cancel</button>
            </div>
            
            <form wire:submit="createIdea" style="display: flex; flex-direction: column; gap: 24px;">
                <div>
                    <label for="title" style="display: block; font-size: 13px; font-weight: 700; color: #334155; margin-bottom: 8px;">Pitch Title</label>
                    <input id="title" type="text" wire:model="title" placeholder="e.g., One-click dark mode toggle for reports"
                           style="width: 100%; border: 1px solid #cbd5e1; background: #f8fafc; border-radius: 14px; padding: 14px 18px; font-size: 14px; color: #1e293b; outline: none;">
                    @error('title') <p style="margin: 6px 0 0 0; font-size: 12px; font-weight: 600; color: #dc2626;">{{ $message }}</p> @enderror
                </div>

                <div>
                    <label for="category" style="display: block; font-size: 13px; font-weight: 700; color: #334155; margin-bottom: 8px;">Category</label>
                    <select id="category" wire:model="category"
                            style="width: 100%; border: 1px solid #cbd5e1; background: #f8fafc; border-radius: 14px; padding: 14px 18px; font-size: 14px; color: #1e293b; outline: none;">
                        <option value="">Select a category...</option>
                        @foreach ($categories as $option)
                            <option value="{{ $option }}">{{ $option }}</option>
                        @endforeach
                    </select>
                    @error('category') <p style="margin: 6px 0 0 0; font-size: 12px; font-weight: 600; color: #dc2626;">{{ $message }}</p> @enderror
                </div>

                <div>
                    <label for="description" style="display: block; font-size: 13px; font-weight: 700; color: #334155; margin-bottom: 8px;">Description</label>
                    <textarea id="description" wire:model="description" rows="4"
                              placeholder="Explain the idea, the problem it solves, and why it matters..."
                              style="width: 100%; border: 1px solid #cbd5e1; background: #f8fafc; border-radius: 14px; padding: 14px 18px; font-size: 14px; color: #1e293b; outline: none; resize: vertical;"></textarea>
                    @error('description') <p style="margin: 6px 0 0 0; font-size: 12px; font-weight: 600; color: #dc2626;">{{ $message }}</p> @enderror
                </div>

                <div style="display: flex; justify-content: flex-end; padding-top: 8px;">
                    <button type="submit"
                            style="background: #4f46e5; color: #ffffff; border: none; border-radius: 14px; padding: 14px 28px; font-size: 14px; font-weight: 700; cursor: pointer; box-shadow: 0 4px 6px -1px rgba(79, 70, 229, 0.2);">
                        Publish Idea
                    </button>
                </div>
            </form>
        </div>
    @endif

    {{-- Filters & Sorting Toolbar --}}
    <div style="background: #ffffff; border: 1px solid #e2e8f0; border-radius: 20px; padding: 20px 24px; display: flex; align-items: center; justify-content: space-between; gap: 20px; margin-bottom: 28px; box-shadow: 0 4px 6px -1px rgba(0,0,0,0.02);">
        <div>
            <select wire:model.live="filterCategory"
                    style="border: 1px solid #cbd5e1; background: #f8fafc; border-radius: 12px; padding: 12px 18px; font-size: 14px; font-weight: 600; color: #334155; outline: none; cursor: pointer;">
                <option value="">📂 All categories</option>
                @foreach ($categories as $option)
                    <option value="{{ $option }}">{{ $option }}</option>
                @endforeach
            </select>
        </div>

        <div style="display: flex; align-items: center; gap: 12px;">
            <span style="font-size: 12px; font-weight: 700; color: #94a3b8; text-transform: uppercase; letter-spacing: 0.05em;">Sort by:</span>
            <select wire:model.live="sort"
                    style="border: 1px solid #cbd5e1; background: #f8fafc; border-radius: 12px; padding: 12px 18px; font-size: 14px; font-weight: 600; color: #334155; outline: none; cursor: pointer;">
                <option value="newest">✨ Newest first</option>
                <option value="top">🔥 Most voted</option>
            </select>
        </div>
    </div>

    {{-- Idea Cards Stream --}}
    <div style="display: flex; flex-direction: column; gap: 24px;">
        @forelse ($ideas as $idea)
            <div style="background: #ffffff; border: 1px solid #e2e8f0; border-radius: 24px; padding: 28px; display: flex; align-items: flex-start; gap: 24px; box-shadow: 0 4px 6px -1px rgba(0, 0, 0, 0.02); transition: all 0.2s ease;" wire:key="idea-{{ $idea->id }}">
                
                {{-- Vote Button --}}
                <button wire:click="toggleVote({{ $idea->id }})"
                        style="{{ $idea->isVotedBy(auth()->user()) ? 'background: #4f46e5; border-color: #4f46e5; color: #ffffff;' : 'background: #f8fafc; border-color: #cbd5e1; color: #475569;' }} width: 64px; height: 72px; flex-shrink: 0; border-radius: 16px; border-width: 1px; border-style: solid; display: flex; flex-direction: column; align-items: center; justify-content: center; cursor: pointer; font-weight: 700; box-shadow: 0 2px 4px rgba(0,0,0,0.02);"
                        aria-label="Toggle vote">
                    <span style="font-size: 11px; line-height: 1;">▲</span>
                    <span style="font-size: 16px; margin-top: 4px;">{{ $idea->votes_count }}</span>
                </button>

                <div style="flex: 1; min-width: 0;">
                    <div style="display: flex; flex-wrap: wrap; align-items: center; gap: 12px; margin-bottom: 10px;">
                        <a href="{{ route('ideas.show', $idea) }}"
                           style="font-size: 19px; font-weight: 800; color: #0f172a; text-decoration: none;" wire:navigate>
                            {{ $idea->title }}
                        </a>
                        <span style="background: #eef2ff; border: 1px solid #e0e7ff; color: #4338ca; border-radius: 9999px; padding: 4px 12px; font-size: 12px; font-weight: 700;">
                            {{ $idea->category }}
                        </span>
                        @if ($idea->status !== 'open')
                            <span style="border-radius: 9999px; padding: 4px 12px; font-size: 12px; font-weight: 700; border-width: 1px; border-style: solid; {{ $idea->status === 'completed' ? 'background: #ecfdf5; color: #047857; border-color: #a7f3d0;' : 'background: #fffbeb; color: #b45309; border-color: #fde68a;' }}">
                                {{ ucfirst($idea->status) }}
                            </span>
                        @endif
                    </div>

                    <p style="color: #475569; font-size: 14px; line-height: 1.6; margin: 0 0 16px 0; display: -webkit-box; -webkit-line-clamp: 2; -webkit-box-orient: vertical; overflow: hidden;">{{ $idea->description }}</p>

                    <div style="display: flex; flex-wrap: wrap; align-items: center; gap: 16px; font-size: 12px; font-weight: 600; color: #94a3b8; padding-top: 16px; border-top: 1px solid #f1f5f9;">
                        <span style="display: flex; align-items: center; gap: 8px; color: #334155;">
                            <span style="width: 26px; height: 26px; border-radius: 50%; background: #e2e8f0; display: flex; align-items: center; justify-content: center; font-size: 11px; font-weight: 800; color: #475569;">
                                {{ strtoupper(substr($idea->user->name, 0, 1)) }}
                            </span>
                            {{ $idea->user->name }}
                        </span>
                        <span>•</span>
                        <span>{{ $idea->created_at->diffForHumans() }}</span>
                        <span>•</span>
                        <a href="{{ route('ideas.show', $idea) }}" style="color: #64748b; text-decoration: none;" wire:navigate>
                            💬 {{ $idea->comments_count }} {{ Str::plural('comment', $idea->comments_count) }}
                        </a>

                        @can('delete', $idea)
                            <span>•</span>
                            <button wire:click="deleteIdea({{ $idea->id }})"
                                    wire:confirm="Delete this idea and all its comments?"
                                    style="background: none; border: none; color: #f87171; font-weight: 700; cursor: pointer; padding: 0;">
                                Delete
                            </button>
                        @endcan
                    </div>
                </div>
            </div>
        @empty
            <div style="background: #ffffff; border: 2px dashed #cbd5e1; border-radius: 24px; padding: 60px 20px; text-align: center;">
                <div style="width: 56px; height: 56px; border-radius: 50%; background: #eef2ff; color: #4f46e5; display: flex; align-items: center; justify-content: center; margin: 0 auto 16px auto; font-size: 24px;">💡</div>
                <h3 style="color: #1e293b; font-size: 16px; font-weight: 800; margin: 0 0 6px 0;">No ideas found</h3>
                <p style="color: #64748b; font-size: 14px; margin: 0;">No ideas yet{{ $filterCategory ? ' in this category' : '' }}. Be the first to share one!</p>
            </div>
        @endforelse
    </div>

    {{-- Pagination --}}
    <div style="margin-top: 32px;">
        {{ $ideas->links() }}
    </div>
</div>