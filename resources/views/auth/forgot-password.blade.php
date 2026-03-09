<x-guest-layout>
    <h2 class="text-xl font-semibold text-slate-800 mb-2">Recuperar contraseña</h2>
    <p class="text-sm text-slate-600 mb-6">Indicá tu email y te enviamos un enlace para elegir una nueva contraseña.</p>

    <x-auth-session-status class="mb-4" :status="session('status')" />

    <form method="POST" action="{{ route('password.email') }}" class="space-y-5">
        @csrf
        <div>
            <x-input-label for="email" :value="__('Email')" />
            <x-text-input id="email" class="block mt-1 w-full" type="email" name="email" :value="old('email')" required autofocus />
            <x-input-error :messages="$errors->get('email')" class="mt-2" />
        </div>
        <x-primary-button class="w-full">Enviar enlace</x-primary-button>
    </form>

    <p class="mt-6 text-center text-sm text-slate-600">
        <a href="{{ route('login') }}" class="font-medium text-indigo-600 hover:text-indigo-500">Volver al login</a>
    </p>
</x-guest-layout>
