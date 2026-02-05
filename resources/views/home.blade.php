<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">
            {{ __('Cursos Disponibles') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <!-- Filtros por categoría -->
            @if($categories->count() > 0)
            <div class="mb-6">
                <div class="flex flex-wrap gap-2">
                    <a href="{{ route('home') }}" 
                       class="px-4 py-2 rounded-lg {{ request()->has('category') ? 'bg-gray-200 dark:bg-gray-700' : 'bg-blue-500 text-white' }}">
                        Todas
                    </a>
                    @foreach($categories as $category)
                    <a href="{{ route('home', ['category' => $category->id]) }}" 
                       class="px-4 py-2 rounded-lg {{ request('category') == $category->id ? 'bg-blue-500 text-white' : 'bg-gray-200 dark:bg-gray-700' }}">
                        {{ $category->name }}
                    </a>
                    @endforeach
                </div>
            </div>
            @endif

            <!-- Grid de cursos -->
            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
                @forelse($courses as $course)
                <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-sm sm:rounded-lg">
                    @if($course->cover_image)
                    <img src="{{ asset('storage/' . $course->cover_image) }}" 
                         alt="{{ $course->title }}" 
                         class="w-full h-48 object-cover">
                    @else
                    <div class="w-full h-48 bg-gray-200 dark:bg-gray-700 flex items-center justify-center">
                        <span class="text-gray-400">Sin imagen</span>
                    </div>
                    @endif
                    
                    <div class="p-6">
                        <h3 class="text-xl font-semibold mb-2">{{ $course->title }}</h3>
                        <p class="text-gray-600 dark:text-gray-400 mb-4 line-clamp-2">
                            {{ $course->short_description ?? Str::limit($course->description, 100) }}
                        </p>
                        
                        <div class="flex items-center justify-between">
                            <span class="text-2xl font-bold text-blue-600 dark:text-blue-400">
                                ${{ number_format($course->price, 2) }}
                            </span>
                            <a href="{{ route('course.show', $course->slug) }}" 
                               class="bg-blue-500 hover:bg-blue-600 text-white px-4 py-2 rounded-lg">
                                Ver más
                            </a>
                        </div>
                    </div>
                </div>
                @empty
                <div class="col-span-full text-center py-12">
                    <p class="text-gray-500 dark:text-gray-400">No hay cursos disponibles</p>
                </div>
                @endforelse
            </div>

            <!-- Paginación -->
            <div class="mt-6">
                {{ $courses->links() }}
            </div>
        </div>
    </div>
</x-app-layout>

