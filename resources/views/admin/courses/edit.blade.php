<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">
            {{ __('Editar Curso') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-4xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white dark:bg-gray-800 shadow-sm sm:rounded-lg">
                <div class="p-6">
                    <form action="{{ route('admin.courses.update', $course) }}" method="POST" enctype="multipart/form-data">
                        @csrf
                        @method('PUT')

                        <div class="space-y-6">
                            <!-- Título -->
                            <div>
                                <x-input-label for="title" :value="__('Título del Curso')" />
                                <x-text-input id="title" 
                                              name="title" 
                                              type="text" 
                                              class="mt-1 block w-full" 
                                              :value="old('title', $course->title)" 
                                              required />
                                <x-input-error class="mt-2" :messages="$errors->get('title')" />
                            </div>

                            <!-- Descripción -->
                            <div>
                                <x-input-label for="description" :value="__('Descripción')" />
                                <textarea id="description" 
                                          name="description" 
                                          rows="5"
                                          class="mt-1 block w-full rounded-md border-gray-300 dark:border-gray-700 dark:bg-gray-900 dark:text-gray-300 focus:border-blue-500 focus:ring-blue-500"
                                          required>{{ old('description', $course->description) }}</textarea>
                                <x-input-error class="mt-2" :messages="$errors->get('description')" />
                            </div>

                            <!-- Precio -->
                            <div>
                                <x-input-label for="price" :value="__('Precio')" />
                                <x-text-input id="price" 
                                              name="price" 
                                              type="number" 
                                              step="0.01"
                                              min="0"
                                              class="mt-1 block w-full" 
                                              :value="old('price', $course->price)" 
                                              required />
                                <x-input-error class="mt-2" :messages="$errors->get('price')" />
                            </div>

                            <!-- Thumbnail -->
                            <div>
                                <x-input-label for="thumbnail" :value="__('Imagen de Portada (Thumbnail)')" />
                                @if($course->thumbnail)
                                <div class="mb-2">
                                    <img src="{{ asset('storage/' . $course->thumbnail) }}" 
                                         alt="Thumbnail actual" 
                                         class="w-32 h-32 object-cover rounded">
                                </div>
                                @endif
                                <input id="thumbnail" 
                                       name="thumbnail" 
                                       type="file" 
                                       accept="image/*"
                                       class="mt-1 block w-full text-sm text-gray-500 file:mr-4 file:py-2 file:px-4 file:rounded-full file:border-0 file:text-sm file:font-semibold file:bg-blue-50 file:text-blue-700 hover:file:bg-blue-100" />
                                <x-input-error class="mt-2" :messages="$errors->get('thumbnail')" />
                            </div>

                            <!-- Estado Activo -->
                            <div class="flex items-center">
                                <input id="is_active" 
                                       name="is_active" 
                                       type="checkbox" 
                                       value="1"
                                       class="rounded border-gray-300 text-blue-600 shadow-sm focus:ring-blue-500"
                                       {{ old('is_active', $course->is_active) ? 'checked' : '' }} />
                                <x-input-label for="is_active" :value="__('Curso Activo')" class="ml-2" />
                            </div>

                            <div class="flex items-center gap-4">
                                <x-primary-button>
                                    {{ __('Actualizar Curso') }}
                                </x-primary-button>
                                <a href="{{ route('admin.courses.index') }}" 
                                   class="text-gray-600 dark:text-gray-400 hover:text-gray-800 dark:hover:text-gray-200">
                                    Cancelar
                                </a>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>

