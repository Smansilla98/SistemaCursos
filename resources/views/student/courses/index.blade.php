<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">
            {{ __('Mis Cursos') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <!-- Mis Cursos -->
            @if($myCourses->count() > 0)
            <div class="mb-8">
                <h3 class="text-xl font-semibold mb-4">Mis Cursos Comprados</h3>
                <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
                    @foreach($myCourses as $course)
                    <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-sm sm:rounded-lg hover:shadow-lg transition-shadow">
                        @if($course->thumbnail)
                        <img src="{{ asset('storage/' . $course->thumbnail) }}" 
                             alt="{{ $course->title }}" 
                             class="w-full h-48 object-cover">
                        @else
                        <div class="w-full h-48 bg-gradient-to-br from-blue-400 to-purple-500 flex items-center justify-center">
                            <span class="text-white text-2xl font-bold">{{ substr($course->title, 0, 2) }}</span>
                        </div>
                        @endif
                        <div class="p-6">
                            <h4 class="text-lg font-semibold mb-2">{{ $course->title }}</h4>
                            <p class="text-gray-600 dark:text-gray-400 mb-4 line-clamp-2">
                                {{ Str::limit($course->description, 100) }}
                            </p>
                            <a href="{{ route('student.courses.show', $course) }}" 
                               class="bg-blue-500 hover:bg-blue-600 text-white px-4 py-2 rounded-lg inline-block w-full text-center">
                                Continuar Aprendiendo
                            </a>
                        </div>
                    </div>
                    @endforeach
                </div>
            </div>
            @endif

            <!-- Cursos Disponibles -->
            <div>
                <h3 class="text-xl font-semibold mb-4">Cursos Disponibles</h3>
                @if($availableCourses->count() > 0)
                <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
                    @foreach($availableCourses as $course)
                    <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-sm sm:rounded-lg hover:shadow-lg transition-shadow">
                        @if($course->thumbnail)
                        <img src="{{ asset('storage/' . $course->thumbnail) }}" 
                             alt="{{ $course->title }}" 
                             class="w-full h-48 object-cover">
                        @else
                        <div class="w-full h-48 bg-gradient-to-br from-blue-400 to-purple-500 flex items-center justify-center">
                            <span class="text-white text-2xl font-bold">{{ substr($course->title, 0, 2) }}</span>
                        </div>
                        @endif
                        <div class="p-6">
                            <h4 class="text-lg font-semibold mb-2">{{ $course->title }}</h4>
                            <p class="text-gray-600 dark:text-gray-400 mb-4 line-clamp-2">
                                {{ Str::limit($course->description, 100) }}
                            </p>
                            <div class="flex items-center justify-between">
                                <span class="text-xl font-bold text-blue-600 dark:text-blue-400">
                                    ${{ number_format($course->price, 2) }}
                                </span>
                                <a href="{{ route('student.courses.show', $course) }}" 
                                   class="bg-blue-500 hover:bg-blue-600 text-white px-4 py-2 rounded-lg">
                                    Ver más
                                </a>
                            </div>
                        </div>
                    </div>
                    @endforeach
                </div>
                @else
                <p class="text-gray-500 dark:text-gray-400 text-center py-8">No hay cursos disponibles</p>
                @endif
            </div>
        </div>
    </div>
</x-app-layout>
