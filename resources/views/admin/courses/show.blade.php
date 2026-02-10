<x-app-layout>
    <x-slot name="header">
        <div class="flex justify-between items-center">
            <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">
                {{ $course->title }}
            </h2>
            <div class="flex gap-2">
                <a href="{{ route('admin.courses.edit', $course) }}" 
                   class="bg-yellow-500 hover:bg-yellow-600 text-white px-4 py-2 rounded-lg">
                    Editar
                </a>
                <a href="{{ route('admin.courses.index') }}" 
                   class="bg-gray-500 hover:bg-gray-600 text-white px-4 py-2 rounded-lg">
                    Volver
                </a>
            </div>
        </div>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <!-- Información del Curso -->
            <div class="bg-white dark:bg-gray-800 shadow-sm sm:rounded-lg mb-6">
                <div class="p-6">
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                        <div>
                            @if($course->thumbnail)
                            <img src="{{ asset('storage/' . $course->thumbnail) }}" 
                                 alt="{{ $course->title }}" 
                                 class="w-full h-64 object-cover rounded-lg mb-4">
                            @endif
                        </div>
                        <div>
                            <h3 class="text-2xl font-bold mb-4">{{ $course->title }}</h3>
                            <p class="text-gray-700 dark:text-gray-300 mb-4 whitespace-pre-line">{{ $course->description }}</p>
                            <div class="space-y-2">
                                <p><strong>Precio:</strong> ${{ number_format($course->price, 2) }}</p>
                                <p><strong>Estado:</strong> 
                                    <span class="px-2 py-1 text-xs rounded-full {{ $course->is_active ? 'bg-green-100 text-green-800' : 'bg-gray-100 text-gray-800' }}">
                                        {{ $course->is_active ? 'Activo' : 'Inactivo' }}
                                    </span>
                                </p>
                                <p><strong>Lecciones:</strong> {{ $course->lessons()->count() }}</p>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Gestión de Lecciones -->
            <div class="bg-white dark:bg-gray-800 shadow-sm sm:rounded-lg mb-6">
                <div class="p-6">
                    <h3 class="text-lg font-semibold mb-4">Lecciones del Curso</h3>
                    
                    <!-- Formulario para agregar lección -->
                    <div class="mb-6 p-4 bg-gray-50 dark:bg-gray-700 rounded-lg">
                        <h4 class="font-semibold mb-3">Agregar Nueva Lección</h4>
                        <form action="{{ route('admin.courses.lessons.add', $course) }}" method="POST" enctype="multipart/form-data">
                            @csrf
                            <div class="grid grid-cols-1 md:grid-cols-4 gap-4">
                                <div>
                                    <x-input-label for="lesson_title" :value="__('Título')" />
                                    <x-text-input id="lesson_title" name="title" type="text" class="mt-1 block w-full" required />
                                </div>
                                <div>
                                    <x-input-label for="lesson_order" :value="__('Orden')" />
                                    <x-text-input id="lesson_order" name="order" type="number" min="0" class="mt-1 block w-full" required />
                                </div>
                                <div>
                                    <x-input-label for="lesson_file_type" :value="__('Tipo')" />
                                    <select id="lesson_file_type" name="file_type" class="mt-1 block w-full rounded-md border-gray-300 dark:border-gray-700 dark:bg-gray-900" required>
                                        <option value="video">Video</option>
                                        <option value="pdf">PDF</option>
                                    </select>
                                </div>
                                <div>
                                    <x-input-label for="lesson_file" :value="__('Archivo')" />
                                    <input id="lesson_file" name="file" type="file" class="mt-1 block w-full text-sm" required />
                                </div>
                            </div>
                            <div class="mt-4">
                                <x-primary-button>Agregar Lección</x-primary-button>
                            </div>
                        </form>
                    </div>

                    <!-- Lista de lecciones -->
                    <div class="space-y-3">
                        @forelse($course->lessons()->orderBy('order')->get() as $lesson)
                        <div class="flex items-center justify-between p-4 bg-gray-50 dark:bg-gray-700 rounded-lg">
                            <div class="flex items-center gap-4">
                                <span class="text-gray-500">#{{ $lesson->order }}</span>
                                <div>
                                    <h5 class="font-semibold">{{ $lesson->title }}</h5>
                                    <p class="text-sm text-gray-500">
                                        Tipo: {{ $lesson->file_type }} | 
                                        Estado: {{ $lesson->is_locked ? 'Bloqueado' : 'Desbloqueado' }}
                                    </p>
                                </div>
                            </div>
                            <form action="{{ route('admin.lessons.delete', $lesson) }}" method="POST" class="inline"
                                  onsubmit="return confirm('¿Eliminar esta lección?')">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="text-red-500 hover:text-red-600">Eliminar</button>
                            </form>
                        </div>
                        @empty
                        <p class="text-gray-500 text-center py-4">No hay lecciones en este curso</p>
                        @endforelse
                    </div>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>

