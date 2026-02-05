<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">
            {{ $course->title }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6">
                    @if($course->cover_image)
                    <img src="{{ asset('storage/' . $course->cover_image) }}" 
                         alt="{{ $course->title }}" 
                         class="w-full h-64 object-cover rounded-lg mb-6">
                    @endif

                    <div class="mb-6">
                        <span class="px-3 py-1 bg-blue-100 dark:bg-blue-900 text-blue-800 dark:text-blue-200 rounded-full text-sm">
                            {{ $course->category->name ?? 'Sin categoría' }}
                        </span>
                        @if($course->teacher)
                        <span class="ml-2 px-3 py-1 bg-gray-100 dark:bg-gray-700 text-gray-800 dark:text-gray-200 rounded-full text-sm">
                            Profesor: {{ $course->teacher->name }}
                        </span>
                        @endif
                    </div>

                    <h1 class="text-3xl font-bold mb-4">{{ $course->title }}</h1>
                    
                    <div class="mb-6">
                        <p class="text-gray-700 dark:text-gray-300 whitespace-pre-line">{{ $course->description }}</p>
                    </div>

                    <div class="flex items-center justify-between border-t pt-6">
                        <div>
                            <span class="text-3xl font-bold text-blue-600 dark:text-blue-400">
                                ${{ number_format($course->price, 2) }}
                            </span>
                        </div>
                        
                        @auth
                            @if(auth()->user()->hasAccessToCourse($course->id))
                                <a href="{{ route('student.courses.show', $course) }}" 
                                   class="bg-green-500 hover:bg-green-600 text-white px-6 py-3 rounded-lg font-semibold">
                                    Acceder al Curso
                                </a>
                            @else
                                <form action="{{ route('student.courses.purchase', $course) }}" method="POST" class="inline">
                                    @csrf
                                    <button type="submit" 
                                            class="bg-blue-500 hover:bg-blue-600 text-white px-6 py-3 rounded-lg font-semibold">
                                        Comprar Curso
                                    </button>
                                </form>
                            @endif
                        @else
                            <a href="{{ route('login') }}" 
                               class="bg-blue-500 hover:bg-blue-600 text-white px-6 py-3 rounded-lg font-semibold">
                                Iniciar Sesión para Comprar
                            </a>
                        @endauth
                    </div>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>

