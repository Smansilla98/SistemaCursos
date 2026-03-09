<x-guest-layout>
    <h2 class="text-xl font-semibold text-slate-800 mb-6">Iniciar sesión</h2>

    <x-auth-session-status class="mb-4" :status="session('status')" />

    <form method="POST" action="{{ route('login') }}" class="space-y-5">
        @csrf

        <div>
            <x-input-label for="email" :value="__('Email')" />
            <x-text-input id="email" class="block mt-1 w-full" type="email" name="email" :value="old('email')" required autofocus autocomplete="username" />
            <x-input-error :messages="$errors->get('email')" class="mt-2" />
        </div>

        <div>
            <x-input-label for="password" :value="__('Contraseña')" />
            <x-text-input id="password" class="block mt-1 w-full" type="password" name="password" required autocomplete="current-password" />
            <x-input-error :messages="$errors->get('password')" class="mt-2" />
        </div>

        <div class="flex items-center">
            <input id="remember_me" type="checkbox" name="remember" class="rounded border-slate-300 text-indigo-600 shadow-sm focus:ring-indigo-500">
            <label for="remember_me" class="ms-2 text-sm text-slate-600">Recordarme</label>
        </div>

        <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4 pt-2">
            @if (Route::has('password.request'))
            <a href="{{ route('password.request') }}" class="text-sm font-medium text-indigo-600 hover:text-indigo-500">
                ¿Olvidaste tu contraseña?
            </a>
            @endif
            <x-primary-button class="w-full sm:w-auto">Entrar</x-primary-button>
        </div>
    </form>

    <p class="mt-6 text-center text-sm text-slate-600">
        ¿No tenés cuenta? <a href="{{ route('register') }}" class="font-medium text-indigo-600 hover:text-indigo-500">Registrarse</a>
    </p>
</x-guest-layout>
