<section class="space-y-6">
    <header>
        <h2 class="text-lg font-semibold text-slate-800">Eliminar cuenta</h2>
        <p class="mt-1 text-sm text-slate-500">Una vez eliminada, todos los datos se borran de forma permanente. Descargá antes lo que quieras conservar.</p>
    </header>

    <x-danger-button x-data="" x-on:click.prevent="$dispatch('open-modal', 'confirm-user-deletion')">
        {{ __('Eliminar cuenta') }}
    </x-danger-button>

    <x-modal name="confirm-user-deletion" :show="$errors->userDeletion->isNotEmpty()" focusable>
        <form method="post" action="{{ route('profile.destroy') }}" class="p-6">
            @csrf
            @method('delete')

            <h2 class="text-lg font-semibold text-slate-800">¿Eliminar tu cuenta?</h2>
            <p class="mt-1 text-sm text-slate-600">Se borrarán todos los datos de forma permanente. Escribí tu contraseña para confirmar.</p>

            <div class="mt-6">
                <x-input-label for="password" value="{{ __('Contraseña') }}" class="sr-only" />
                <x-text-input id="password" name="password" type="password" class="mt-1 block w-full" placeholder="{{ __('Contraseña') }}" />
                <x-input-error :messages="$errors->userDeletion->get('password')" class="mt-2" />
            </div>

            <div class="mt-6 flex justify-end gap-3">
                <x-secondary-button x-on:click="$dispatch('close')">Cancelar</x-secondary-button>
                <x-danger-button>Eliminar cuenta</x-danger-button>
            </div>
        </form>
    </x-modal>
</section>
