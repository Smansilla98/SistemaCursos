<x-app-layout>
    <x-slot name="header">
        <h1 class="text-2xl font-bold text-slate-800 tracking-tight">
            {{ __('Cursos disponibles') }}
        </h1>
        <p class="mt-1 text-sm text-slate-500">Encontrá el curso que necesitás y empezá a aprender.</p>
    </x-slot>

    <div class="py-8">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
                @forelse($courses as $course)
                <article class="card-nova hover:shadow-nova-md transition-shadow">
                    @if($course->thumbnail)
                    <img src="{{ asset('storage/' . $course->thumbnail) }}" alt="{{ $course->title }}" class="w-full h-48 object-cover rounded-t-xl">
                    @else
                    <div class="w-full h-48 bg-gradient-to-br from-indigo-500 to-indigo-700 flex items-center justify-center rounded-t-xl">
                        <span class="text-white text-2xl font-bold">{{ substr($course->title, 0, 2) }}</span>
                    </div>
                    @endif
                    <div class="p-6">
                        <h2 class="text-lg font-semibold text-slate-800 mb-2">{{ $course->title }}</h2>
                        <p class="text-slate-600 text-sm mb-4 line-clamp-2">{{ Str::limit($course->description, 100) }}</p>
                        <div class="flex items-center justify-between">
                            <span class="text-xl font-bold text-indigo-600">${{ number_format($course->price, 2) }}</span>
                            <a href="{{ route('course.show', $course) }}" class="btn-nova text-sm">
                                Ver más
                            </a>
                        </div>
                    </div>
                </article>
                @empty
                <div class="col-span-full text-center py-16">
                    <p class="text-slate-500">No hay cursos disponibles por el momento.</p>
                </div>
                @endforelse
            </div>

            @if($courses->hasPages())
            <div class="mt-8">
                {{ $courses->links() }}
            </div>
            @endif
        </div>
    </div>
</x-app-layout>
