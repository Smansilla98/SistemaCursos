<x-app-layout>
    <x-slot name="header">
        <h1 class="text-2xl font-bold text-slate-800 tracking-tight">Crear curso</h1>
        <p class="mt-1 text-sm text-slate-500">Nuevo curso</p>
    </x-slot>

    <div class="py-8">
        <div class="max-w-4xl mx-auto sm:px-6 lg:px-8">
            <div class="card-nova p-6">
                <form action="{{ route('admin.courses.store') }}" method="POST" enctype="multipart/form-data" class="space-y-6">
                    @csrf

                    <div>
                        <x-input-label for="title" :value="__('Título del curso')" />
                        <x-text-input id="title" name="title" type="text" class="mt-1 block w-full" :value="old('title')" required autofocus />
                        <x-input-error class="mt-2" :messages="$errors->get('title')" />
                    </div>

                    <div>
                        <x-input-label for="description" :value="__('Descripción')" />
                        <textarea id="description" name="description" rows="5" class="input-nova mt-1 block w-full rounded-lg border-slate-300 text-slate-800 focus:border-indigo-500 focus:ring-indigo-500" required>{{ old('description') }}</textarea>
                        <x-input-error class="mt-2" :messages="$errors->get('description')" />
                    </div>

                    <div>
                        <x-input-label for="price" :value="__('Precio')" />
                        <x-text-input id="price" name="price" type="number" step="0.01" min="0" class="mt-1 block w-full" :value="old('price')" required />
                        <x-input-error class="mt-2" :messages="$errors->get('price')" />
                    </div>

                    <div>
                        <x-input-label for="thumbnail" :value="__('Imagen de portada')" />
                        <input id="thumbnail" name="thumbnail" type="file" accept="image/*" class="mt-1 block w-full text-sm text-slate-500 file:mr-4 file:py-2 file:px-4 file:rounded-lg file:border-0 file:text-sm file:font-medium file:bg-indigo-50 file:text-indigo-700 hover:file:bg-indigo-100" />
                        <x-input-error class="mt-2" :messages="$errors->get('thumbnail')" />
                    </div>

                    <div class="flex items-center">
                        <input id="is_active" name="is_active" type="checkbox" value="1" class="rounded border-slate-300 text-indigo-600 shadow-sm focus:ring-indigo-500" {{ old('is_active', true) ? 'checked' : '' }} />
                        <x-input-label for="is_active" :value="__('Curso activo')" class="ms-2" />
                    </div>

                    <div class="flex items-center gap-4 pt-2">
                        <x-primary-button>Crear curso</x-primary-button>
                        <a href="{{ route('admin.courses.index') }}" class="text-sm font-medium text-slate-600 hover:text-slate-800">Cancelar</a>
                    </div>
                </form>
            </div>
        </div>
    </div>
</x-app-layout>
