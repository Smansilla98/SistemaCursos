<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">
            {{ $course->title }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            @if(!$hasAccess)
            <!-- Sin acceso - Opción de compra -->
            <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-sm sm:rounded-lg mb-6">
                <div class="p-6">
                    <h3 class="text-xl font-semibold mb-4">Acceso al Curso</h3>
                    
                    <div class="mb-6">
                        <p class="mb-4 text-lg">
                            Precio: <span class="text-3xl font-bold text-blue-600 dark:text-blue-400">${{ number_format($course->price, 2) }}</span>
                        </p>
                        <form action="{{ route('student.courses.purchase', $course) }}" method="POST" class="inline">
                            @csrf
                            <button type="submit" 
                                    class="bg-blue-500 hover:bg-blue-600 text-white px-8 py-3 rounded-lg font-semibold text-lg">
                                Comprar con MercadoPago
                            </button>
                        </form>
                    </div>

                    <div class="border-t pt-6">
                        <p class="text-sm text-gray-600 dark:text-gray-400 mb-2">
                            Al comprar este curso, obtendrás acceso inmediato a todo el contenido una vez que el pago sea aprobado.
                        </p>
                    </div>
                </div>
            </div>
            @endif

            <!-- Información del Curso -->
            <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-sm sm:rounded-lg mb-6">
                <div class="p-6">
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                        <div>
                            @if($course->thumbnail)
                            <img src="{{ asset('storage/' . $course->thumbnail) }}" 
                                 alt="{{ $course->title }}" 
                                 class="w-full h-64 object-cover rounded-lg">
                            @endif
                        </div>
                        <div>
                            <h1 class="text-3xl font-bold mb-4">{{ $course->title }}</h1>
                            <p class="text-gray-700 dark:text-gray-300 whitespace-pre-line mb-4">{{ $course->description }}</p>
                            <p class="text-2xl font-bold text-blue-600 dark:text-blue-400 mb-4">
                                ${{ number_format($course->price, 2) }}
                            </p>
                        </div>
                    </div>
                </div>
            </div>

            @if($hasAccess)
            <!-- Lecciones del Curso -->
            <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6">
                    <h3 class="text-xl font-semibold mb-4">Contenido del Curso</h3>
                    
                    @forelse($course->lessons()->orderBy('order')->get() as $lesson)
                    <div class="mb-4 p-4 border border-gray-200 dark:border-gray-700 rounded-lg">
                        <div class="flex items-center justify-between">
                            <div class="flex items-center gap-4">
                                <span class="text-gray-500 font-semibold">#{{ $lesson->order }}</span>
                                <div>
                                    <h4 class="font-semibold">{{ $lesson->title }}</h4>
                                    <p class="text-sm text-gray-500">
                                        Tipo: {{ $lesson->file_type === 'video' ? 'Video' : 'PDF' }}
                                    </p>
                                </div>
                            </div>
                            <div>
                                @if($lesson->isVideo())
                                <a href="{{ asset('storage/' . $lesson->file_path) }}" 
                                   target="_blank"
                                   class="bg-red-500 hover:bg-red-600 text-white px-4 py-2 rounded-lg inline-flex items-center gap-2">
                                    <svg class="w-5 h-5" fill="currentColor" viewBox="0 0 20 20">
                                        <path d="M6.3 2.841A1.5 1.5 0 004 4.11V15.89a1.5 1.5 0 002.3 1.269l9.344-5.89a1.5 1.5 0 000-2.538L6.3 2.84z"/>
                                    </svg>
                                    Ver Video
                                </a>
                                @else
                                <a href="{{ asset('storage/' . $lesson->file_path) }}" 
                                   target="_blank"
                                   class="bg-red-500 hover:bg-red-600 text-white px-4 py-2 rounded-lg inline-flex items-center gap-2">
                                    <svg class="w-5 h-5" fill="currentColor" viewBox="0 0 20 20">
                                        <path fill-rule="evenodd" d="M4 4a2 2 0 012-2h4.586A2 2 0 0112 2.586L15.414 6A2 2 0 0116 7.414V16a2 2 0 01-2 2H6a2 2 0 01-2-2V4z" clip-rule="evenodd"/>
                                    </svg>
                                    Ver PDF
                                </a>
                                @endif
                            </div>
                        </div>
                    </div>
                    @empty
                    <p class="text-gray-500 dark:text-gray-400 text-center py-8">
                        Este curso aún no tiene contenido disponible.
                    </p>
                    @endforelse
                </div>
            </div>
            @else
            <!-- Vista previa bloqueada -->
            <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6">
                    <h3 class="text-xl font-semibold mb-4">Contenido del Curso</h3>
                    <p class="text-gray-500 dark:text-gray-400 text-center py-8">
                        Compra este curso para acceder a todo el contenido.
                    </p>
                    <p class="text-center text-sm text-gray-400">
                        Este curso tiene {{ $course->lessons()->count() }} lecciones disponibles.
                    </p>
                </div>
            </div>
            @endif
        </div>
    </div>
</x-app-layout>
