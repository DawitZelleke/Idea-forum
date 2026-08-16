<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <link rel="icon" href="{{ asset('logo.svg') }}" type="image/svg+xml">
    <title>{{ $title ?? config('app.name', 'IdeaNest') }}</title>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>
<body style="background-color: #f8fafc; color: #1e293b; min-height: 100vh; margin: 0; font-family: ui-sans-serif, system-ui, sans-serif;">
    
    <nav style="background-color: #0f172a; border-bottom: 1px solid #334155; position: sticky; top: 0; z-index: 50; box-shadow: 0 10px 25px -5px rgba(0, 0, 0, 0.3);">
        <div style="max-width: 56rem; margin: 0 auto; display: flex; align-items: center; justify-content: space-between; padding: 20px 24px;">
            
            <!-- Logo & Brand (Original bulb icon restored) -->
            <a href="{{ auth()->check() ? route('ideas.index') : route('login') }}" style="display: flex; align-items: center; gap: 12px; text-decoration: none;" wire:navigate>
                <div style="width: 40px; height: 40px; border-radius: 12px; background: linear-gradient(135deg, #6366f1, #a855f7); display: flex; align-items: center; justify-content: center; box-shadow: 0 4px 12px rgba(99, 102, 241, 0.4);">
                    <img src="{{ asset('logo.svg') }}" alt="" style="width: 24px; height: 24px;" aria-hidden="true">
                </div>
                <span style="color: #ffffff !important; font-size: 22px; font-weight: 800; letter-spacing: -0.025em;">
                    IdeaNest
                </span>
            </a>

            <!-- Right Actions -->
            <div style="display: flex; align-items: center; gap: 16px;">
                @auth
                    <!-- Username badge -->
                    <div style="display: flex; align-items: center; gap: 10px; background-color: rgba(255, 255, 255, 0.08); border: 1px solid rgba(255, 255, 255, 0.15); padding: 6px 14px 6px 6px; border-radius: 9999px;">
                        <div style="width: 28px; height: 28px; border-radius: 50%; background: linear-gradient(135deg, #6366f1, #a855f7); display: flex; align-items: center; justify-content: center; font-size: 11px; font-weight: bold; color: white;">
                            {{ strtoupper(substr(auth()->user()->name, 0, 1)) }}
                        </div>
                        <span style="color: #ffffff !important; font-size: 14px; font-weight: 600;">
                            {{ auth()->user()->name }}
                        </span>
                    </div>

                    <!-- Logout Button -->
                    <form method="POST" action="{{ route('logout') }}" style="margin: 0;">
                        @csrf
                        <button type="submit" style="background-color: rgba(239, 68, 68, 0.2); border: 1px solid rgba(239, 68, 68, 0.4); color: #fca5a5; padding: 8px 18px; border-radius: 9999px; font-size: 14px; font-weight: 700; cursor: pointer;">
                            Log out
                        </button>
                    </form>
                @else
                    <a href="{{ route('login') }}" style="color: #cbd5e1; text-decoration: none; font-weight: 500;" wire:navigate>Log in</a>
                    <a href="{{ route('register') }}" style="background: linear-gradient(135deg, #6366f1, #a855f7); color: #ffffff; padding: 8px 18px; border-radius: 9999px; font-weight: 600; text-decoration: none;" wire:navigate>
                        Sign up
                    </a>
                @endauth
            </div>
        </div>
    </nav>

    <main style="max-width: 56rem; margin: 0 auto; padding: 32px 16px;">
        @if (session('status'))
            <div style="margin-bottom: 24px; border-radius: 8px; border: 1px solid #6ee7b7; background-color: #ecfdf5; padding: 12px 16px; font-size: 14px; color: #065f46;" role="status">
                {{ session('status') }}
            </div>
        @endif

        {{ $slot }}
    </main>

    <footer style="padding: 32px 0; text-align: center; font-size: 12px; color: #94a3b8;">
        IdeaNest &mdash; share it, vote on it, build it.
    </footer>
</body>
</html>