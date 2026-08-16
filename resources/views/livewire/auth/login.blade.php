<div class="mx-auto max-w-md">
    <h1 class="mb-6 text-2xl font-bold">Log in</h1>

    <form wire:submit="login" class="space-y-4 rounded-xl bg-white p-6 shadow">
        <div>
            <label for="email" class="mb-1 block text-sm font-medium">Email</label>
            <input id="email" type="email" wire:model="email" autocomplete="email"
                   class="w-full rounded-md border border-slate-300 px-3 py-2 focus:border-indigo-500 focus:outline-none focus:ring-1 focus:ring-indigo-500">
            @error('email') <p class="mt-1 text-sm text-red-600">{{ $message }}</p> @enderror
        </div>

        <div>
            <label for="password" class="mb-1 block text-sm font-medium">Password</label>
            <input id="password" type="password" wire:model="password" autocomplete="current-password"
                   class="w-full rounded-md border border-slate-300 px-3 py-2 focus:border-indigo-500 focus:outline-none focus:ring-1 focus:ring-indigo-500">
            @error('password') <p class="mt-1 text-sm text-red-600">{{ $message }}</p> @enderror
        </div>

        <label class="flex items-center gap-2 text-sm">
            <input type="checkbox" wire:model="remember" class="rounded border-slate-300">
            Remember me
        </label>

        <button type="submit"
                class="w-full rounded-md bg-indigo-600 px-4 py-2 font-medium text-white hover:bg-indigo-500">
            Log in
        </button>

        <p class="text-center text-sm text-slate-500">
            No account?
            <a href="{{ route('register') }}" class="font-medium text-indigo-600 hover:underline" wire:navigate>
                Sign up
            </a>
        </p>
    </form>
</div>
