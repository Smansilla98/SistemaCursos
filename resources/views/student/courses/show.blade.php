<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">
            {{ $course->title }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            @if(!$hasAccess)
            <!-- Sin acceso -->
            <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-sm sm:rounded-lg mb-6">
                <div class="p-6">
                    <h3 class="text-xl font-semibold mb-4">Acceso al Curso</h3>
                    
                    @if($course->requires_payment)
                    <div class="mb-6">
                        <p class="mb-4">Precio: <span class="text-2xl font-bold text-blue-600">${{ number_format($course->price, 2) }}</span></p>
                        <form action="{{ route('student.courses.purchase', $course) }}" method="POST" class="inline">
                            @csrf
                            <button type="submit" 
                                    class="bg-blue-500 hover:bg-blue-600 text-white px-6 py-3 rounded-lg font-semibold">
                                Comprar con MercadoPago
                            </button>
                        </form>
                    </div>
                    @endif

                    <!-- Ingresar Clave -->
                    <div class="border-t pt-6">
                        <h4 class="font-semibold mb-2">¿Tienes una clave de acceso?</h4>
                        <form action="{{ route('student.access-keys.validate') }}" method="POST">
                            @csrf
                            <input type="hidden" name="course_id" value="{{ $course->id }}">
                            <div class="flex gap-2">
                                <input type="text" 
                                       name="key" 
                                       placeholder="Ingresa tu clave"
                                       class="flex-1 rounded-lg border-gray-300 dark:border-gray-700 dark:bg-gray-900 dark:text-gray-300 focus:border-blue-500 focus:ring-blue-500"
                                       required>
                                <button type="submit" 
                                        class="bg-green-500 hover:bg-green-600 text-white px-6 py-2 rounded-lg">
                                    Validar
                                </button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
            @endif

            <!-- Información del Curso -->
            <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-sm sm:rounded-lg mb-6">
                <div class="p-6">
                    @if($course->cover_image)
                    <img src="{{ asset('storage/' . $course->cover_image) }}" 
                         alt="{{ $course->title }}" 
                         class="w-full h-64 object-cover rounded-lg mb-6">
                    @endif

                    <h1 class="text-3xl font-bold mb-4">{{ $course->title }}</h1>
                    <p class="text-gray-700 dark:text-gray-300 whitespace-pre-line mb-6">{{ $course->description }}</p>

                    @if($course->teacher)
                    <p class="text-sm text-gray-500 dark:text-gray-400">
                        Profesor: {{ $course->teacher->name }}
                    </p>
                    @endif
                </div>
            </div>

            @if($hasAccess)
            <!-- Módulos y Contenido -->
            <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6">
                    <h3 class="text-xl font-semibold mb-4">Contenido del Curso</h3>
                    
                    @forelse($course->modules as $module)
                    <div class="mb-6 border-b pb-4 last:border-b-0">
                        <h4 class="text-lg font-semibold mb-2">{{ $module->title }}</h4>
                        @if($module->description)
                        <p class="text-gray-600 dark:text-gray-400 mb-3">{{ $module->description }}</p>
                        @endif

                        @if($module->files->count() > 0)
                        <div class="space-y-2">
                            @foreach($module->files as $file)
                            <div class="flex items-center justify-between p-3 bg-gray-50 dark:bg-gray-700 rounded-lg">
                                <div class="flex items-center gap-3">
                                    @if($file->isVideo())
                                    <svg class="w-5 h-5 text-red-500" fill="currentColor" viewBox="0 0 20 20">
                                        <path d="M2 6a2 2 0 012-2h6a2 2 0 012 2v8a2 2 0 01-2 2H4a2 2 0 01-2-2V6zM14.553 7.106A1 1 0 0014 8v4a1 1 0 00.553.894l2 1A1 1 0 0018 13V7a1 1 0 00-1.447-.894l-2 1z"/>
                                    </svg>
                                    @elseif($file->isPdf())
                                    <svg class="w-5 h-5 text-red-500" fill="currentColor" viewBox="0 0 20 20">
                                        <path fill-rule="evenodd" d="M4 4a2 2 0 012-2h4.586A2 2 0 0112 2.586L15.414 6A2 2 0 0116 7.414V16a2 2 0 01-2 2H6a2 2 0 01-2-2V4z" clip-rule="evenodd"/>
                                    </svg>
                                    @else
                                    <svg class="w-5 h-5 text-blue-500" fill="currentColor" viewBox="0 0 20 20">
                                        <path fill-rule="evenodd" d="M4 4a2 2 0 012-2h4.586A2 2 0 0112 2.586L15.414 6A2 2 0 0116 7.414V16a2 2 0 01-2 2H6a2 2 0 01-2-2V4z" clip-rule="evenodd"/>
                                    </svg>
                                    @endif
                                    <span>{{ $file->name }}</span>
                                </div>
                                <a href="{{ asset('storage/' . $file->file_path) }}" 
                                   target="_blank"
                                   class="text-blue-500 hover:text-blue-600">
                                    Ver/Descargar
                                </a>
                            </div>
                            @endforeach
                        </div>
                        @endif
                    </div>
                    @empty
                    <p class="text-gray-500 dark:text-gray-400">Este curso aún no tiene contenido disponible.</p>
                    @endforelse
                </div>
            </div>
            @endif
        </div>
    </div>
</x-app-layout>

