<x-guest-layout>
    <h2 class="text-xl font-semibold text-slate-800 mb-2">Confirmar contraseña</h2>
    <p class="text-sm text-slate-600 mb-6">Esta zona es segura. Confirmá tu contraseña para continuar.</p>

    <form method="POST" action="{{ route('password.confirm') }}" class="space-y-5">
        @csrf
        <div>
            <x-input-label for="password" :value="__('Contraseña')" />
            <x-text-input id="password" class="block mt-1 w-full" type="password" name="password" required autocomplete="current-password" />
            <x-input-error :messages="$errors->get('password')" class="mt-2" />
        </div>
        <x-primary-button class="w-full">Confirmar</x-primary-button>
    </form>
</x-guest-layout>
