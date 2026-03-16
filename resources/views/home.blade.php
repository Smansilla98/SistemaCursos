<x-app-layout>
    <x-slot name="header">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <h1 class="text-3xl font-bold text-slate-800 tracking-tight">
                Encuentra nuevos cursos
            </h1>
        </div>
    </x-slot>

    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 pb-12">
        {{-- Tabs (solo Cursos por ahora) --}}
        <div class="border-b border-slate-200 mb-6">
            <nav class="flex gap-8" aria-label="Tipo de contenido">
                <a href="{{ route('courses.index') }}" class="inline-flex items-center gap-2 py-4 px-1 border-b-2 border-indigo-600 text-sm font-medium text-indigo-600">
                    <svg class="w-5 h-5" fill="currentColor" viewBox="0 0 20 20"><path d="M6.3 2.841A1.5 1.5 0 004 4.11V15.89a1.5 1.5 0 002.3 1.269l9.344-5.89a1.5 1.5 0 000-2.538L6.3 2.84z"/></svg>
                    Cursos
                </a>
            </nav>
        </div>

        {{-- Búsqueda y filtros --}}
        <form action="{{ route('courses.index') }}" method="GET" class="mb-6">
            <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4">
                <div class="flex flex-wrap items-center gap-3 flex-1 min-w-0">
                    <div class="relative flex-1 min-w-[200px] max-w-md">
                        <input
                            type="search"
                            name="q"
                            value="{{ old('q', $search) }}"
                            placeholder="Buscar..."
                            class="input-nova w-full pl-10 pr-4 rounded-xl border-slate-300 focus:border-indigo-500 focus:ring-indigo-500"
                        />
                        <span class="absolute left-3 top-1/2 -translate-y-1/2 text-slate-400">
                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"/></svg>
                        </span>
                    </div>
                    @if($search)
                        <span class="text-sm text-slate-600 flex items-center gap-1">
                            Estás buscando:
                            <a href="{{ route('courses.index', request()->except('q')) }}" class="inline-flex items-center gap-1 font-medium text-indigo-600 hover:text-indigo-700">
                                {{ $search }}
                                <svg class="w-4 h-4 text-slate-400 hover:text-slate-600" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M4.293 4.293a1 1 0 011.414 0L10 8.586l4.293-4.293a1 1 0 111.414 1.414L11.414 10l4.293 4.293a1 1 0 01-1.414 1.414L10 11.414l-4.293 4.293a1 1 0 01-1.414-1.414L8.586 10 4.293 5.707a1 1 0 010-1.414z" clip-rule="evenodd"/></svg>
                            </a>
                        </span>
                    @endif
                </div>
                <div class="flex flex-wrap items-center gap-3">
                    <span class="text-sm text-slate-600">
                        {{ $courses->total() }} resultado{{ $courses->total() !== 1 ? 's' : '' }}
                        @if($courses->hasPages())
                            – {{ $courses->currentPage() }}/{{ $courses->lastPage() }} páginas
                        @endif
                    </span>
                    <label class="flex items-center gap-2 text-sm text-slate-700">
                        Ordenar por
                        <select name="sort" onchange="this.form.submit()" class="input-nova py-1.5 pl-2 pr-8 rounded-lg border-slate-300 text-sm">
                            <option value="recomendados" {{ ($sort ?? '') === 'recomendados' ? 'selected' : '' }}>Recomendados</option>
                            <option value="recientes" {{ ($sort ?? '') === 'recientes' ? 'selected' : '' }}>Más recientes</option>
                            <option value="precio_asc" {{ ($sort ?? '') === 'precio_asc' ? 'selected' : '' }}>Precio: menor a mayor</option>
                            <option value="precio_desc" {{ ($sort ?? '') === 'precio_desc' ? 'selected' : '' }}>Precio: mayor a menor</option>
                        </select>
                    </label>
                    @if($search)
                        <input type="hidden" name="q" value="{{ $search }}" />
                    @endif
                </div>
            </div>
        </form>

        <div class="flex flex-col lg:flex-row gap-8">
            {{-- Sidebar Categorías --}}
            <aside class="lg:w-56 shrink-0">
                <div class="card-nova p-4 sticky top-24">
                    <h3 class="text-sm font-semibold text-slate-800 uppercase tracking-wider mb-3">Categorías</h3>
                    <ul class="space-y-1">
                        <li>
                            <a href="{{ route('courses.index', request()->only('sort')) }}" class="flex items-center gap-2 py-2 text-sm text-slate-700 hover:text-indigo-600 transition-colors">
                                <svg class="w-4 h-4 text-slate-400" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M7.293 14.707a1 1 0 001.414 0l10-10a1 1 0 00-1.414-1.414L8 12.586 2.707 3.293a1 1 0 000 1.414z" clip-rule="evenodd"/></svg>
                                Todos los cursos
                            </a>
                        </li>
                    </ul>
                </div>
            </aside>

            {{-- Grid de cursos --}}
            <div class="flex-1 min-w-0">
                <div class="grid grid-cols-1 sm:grid-cols-2 xl:grid-cols-3 gap-6">
                    @forelse($courses as $course)
                        <a href="{{ route('course.show', $course) }}" class="group block rounded-2xl overflow-hidden bg-gradient-to-br from-indigo-600 to-indigo-800 shadow-nova hover:shadow-nova-md transition-all duration-200 hover:scale-[1.02]">
                            @if($course->thumbnail)
                                <div class="aspect-video relative">
                                    <img src="{{ asset('storage/' . $course->thumbnail) }}" alt="{{ $course->title }}" class="w-full h-full object-cover group-hover:opacity-90 transition-opacity" />
                                </div>
                            @else
                                <div class="aspect-video flex items-center justify-center bg-indigo-700/50">
                                    <span class="w-16 h-16 rounded-full bg-white/20 flex items-center justify-center text-white text-2xl font-bold group-hover:bg-white/30 transition-colors">
                                        {{ substr($course->title, 0, 2) }}
                                    </span>
                                </div>
                            @endif
                            <div class="p-5">
                                <h2 class="text-lg font-semibold text-white mb-1 line-clamp-2 group-hover:text-white/95">
                                    {{ $course->title }}
                                </h2>
                                <p class="text-sm text-indigo-200">
                                    {{ $course->users_count ?? 0 }} estudiante{{ ($course->users_count ?? 0) !== 1 ? 's' : '' }}
                                </p>
                                <p class="mt-2 text-white font-semibold">${{ number_format($course->price, 2) }}</p>
                            </div>
                        </a>
                    @empty
                        <div class="col-span-full card-nova p-12 text-center">
                            <p class="text-slate-500">
                                @if($search)
                                    No hay resultados para "{{ $search }}". Probá con otros términos.
                                @else
                                    No hay cursos disponibles por el momento.
                                @endif
                            </p>
                            @if($search)
                                <a href="{{ route('courses.index') }}" class="inline-block mt-4 btn-nova">Ver todos</a>
                            @endif
                        </div>
                    @endforelse
                </div>

                @if($courses->hasPages())
                    <div class="mt-8 flex justify-center">
                        {{ $courses->links() }}
                    </div>
                @endif
            </div>
        </div>
    </div>
</x-app-layout>
