<x-app-layout>
    <x-slot name="header">
        <h1 class="text-2xl font-bold text-slate-800 tracking-tight">Editar usuario</h1>
        <p class="mt-1 text-sm text-slate-500">{{ $user->name }}</p>
    </x-slot>

    <div class="py-8">
        <div class="max-w-4xl mx-auto sm:px-6 lg:px-8">
            <div class="card-nova p-6">
                <form action="{{ route('admin.users.update', $user) }}" method="POST" class="space-y-6">
                    @csrf
                    @method('PUT')
                    <div>
                        <x-input-label for="name" :value="__('Nombre')" />
                        <x-text-input id="name" name="name" type="text" class="mt-1 block w-full" :value="old('name', $user->name)" required />
                        <x-input-error class="mt-2" :messages="$errors->get('name')" />
                    </div>
                    <div>
                        <x-input-label for="email" :value="__('Email')" />
                        <x-text-input id="email" name="email" type="email" class="mt-1 block w-full" :value="old('email', $user->email)" required />
                        <x-input-error class="mt-2" :messages="$errors->get('email')" />
                    </div>
                    <div>
                        <x-input-label for="password" :value="__('Nueva contraseña (dejar vacío para no cambiar)')" />
                        <x-text-input id="password" name="password" type="password" class="mt-1 block w-full" />
                        <x-input-error class="mt-2" :messages="$errors->get('password')" />
                    </div>
                    <div>
                        <x-input-label for="password_confirmation" :value="__('Confirmar nueva contraseña')" />
                        <x-text-input id="password_confirmation" name="password_confirmation" type="password" class="mt-1 block w-full" />
                    </div>
                    <div>
                        <x-input-label for="role" :value="__('Rol')" />
                        <select id="role" name="role" class="input-nova mt-1 block w-full rounded-lg border-slate-300" required>
                            <option value="student" {{ $user->hasRole('student') ? 'selected' : '' }}>Alumno</option>
                            <option value="profesor" {{ $user->hasRole('profesor') ? 'selected' : '' }}>Profesor</option>
                            <option value="admin" {{ $user->hasRole('admin') ? 'selected' : '' }}>Administrador</option>
                        </select>
                        <x-input-error class="mt-2" :messages="$errors->get('role')" />
                    </div>
                    <div class="flex items-center gap-4 pt-2">
                        <x-primary-button>Actualizar usuario</x-primary-button>
                        <a href="{{ route('admin.users.index') }}" class="text-sm font-medium text-slate-600 hover:text-slate-800">Cancelar</a>
                    </div>
                </form>
            </div>
        </div>
    </div>
</x-app-layout>
