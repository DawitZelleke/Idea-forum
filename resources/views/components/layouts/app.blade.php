<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <link rel="icon" href="{{ asset('logo.svg') }}" type="image/svg+xml">
    <title>{{ $title ?? config('app.name', 'IdeaNest') }}</title>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>
<body class="min-h-screen bg-slate-50 text-slate-800 antialiased">
    <nav class="bg-indigo-600 text-white shadow">
        <div class="mx-auto flex max-w-4xl items-center justify-between px-4 py-3">
            <a href="{{ auth()->check() ? route('ideas.index') : route('login') }}"
               class="flex items-center gap-2 text-lg font-bold tracking-tight" wire:navigate>
                <img src="{{ asset('logo.svg') }}" alt="" class="h-7 w-7" aria-hidden="true">
                IdeaNest
            </a>

            <div class="flex items-center gap-4 text-sm">
                @auth
                    <span class="hidden sm:inline text-indigo-100">{{ auth()->user()->name }}</span>
                    <form method="POST" action="{{ route('logout') }}">
                        @csrf
                        <button type="submit"
                                class="rounded-md bg-indigo-500 px-3 py-1.5 font-medium hover:bg-indigo-400">
                            Log out
                        </button>
                    </form>
                @else
                    <a href="{{ route('login') }}" class="hover:underline" wire:navigate>Log in</a>
                    <a href="{{ route('register') }}"
                       class="rounded-md bg-white px-3 py-1.5 font-medium text-indigo-700 hover:bg-indigo-50"
                       wire:navigate>
                        Sign up
                    </a>
                @endauth
            </div>
        </div>
    </nav>

    <main class="mx-auto max-w-4xl px-4 py-8">
        @if (session('status'))
            <div class="mb-6 rounded-md border border-emerald-300 bg-emerald-50 px-4 py-3 text-sm text-emerald-800"
                 role="status">
                {{ session('status') }}
            </div>
        @endif

        {{ $slot }}
    </main>

    <footer class="py-8 text-center text-xs text-slate-400">
        IdeaNest &mdash; share it, vote on it, build it.
    </footer>
</body>
</html>
