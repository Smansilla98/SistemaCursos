<x-app-layout>
    <div class="-mx-4 sm:-mx-6 lg:-mx-8 -mt-8 -mb-8">
        {{-- Barra superior: progreso y acciones --}}
        <header class="bg-slate-900 text-white border-b border-slate-800">
            <div class="px-4 sm:px-6 lg:px-8 py-3">
                <div class="flex flex-wrap items-center justify-between gap-4">
                    <div class="flex items-center gap-4 min-w-0">
                        <a href="{{ route('student.courses.show', $course) }}" class="shrink-0 flex items-center gap-2 text-slate-300 hover:text-white transition-colors">
                            <span class="text-sm font-medium">{{ $course->title }}</span>
                        </a>
                        <div class="hidden sm:flex items-center gap-3 flex-1 min-w-0 max-w-md">
                            <div class="flex-1 h-2 bg-slate-700 rounded-full overflow-hidden">
                                <div class="h-full bg-emerald-500 rounded-full transition-all duration-300" style="width: {{ $progressPercent }}%"></div>
                            </div>
                            <span class="text-xs text-slate-400 shrink-0">{{ $progressPercent }}% completado ({{ $completedCount }} de {{ $totalLessons }} clases)</span>
                        </div>
                    </div>
                    <div class="flex items-center gap-2">
                        <a href="{{ route('student.courses.show', $course) }}" class="text-sm text-slate-300 hover:text-white px-3 py-1.5 rounded-lg hover:bg-slate-800 transition-colors">Ir al curso</a>
                        <a href="{{ route('student.courses.index') }}" class="text-sm text-slate-300 hover:text-white px-3 py-1.5 rounded-lg hover:bg-slate-800 transition-colors">Mis cursos</a>
                        <x-dropdown align="right" width="48">
                            <x-slot name="trigger">
                                <button class="flex items-center gap-2 p-1.5 rounded-lg hover:bg-slate-800 text-slate-300 hover:text-white transition-colors">
                                    <svg class="w-5 h-5" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M10 9a3 3 0 100-6 3 3 0 000 6zm-7 9a7 7 0 1114 0H3z" clip-rule="evenodd"/></svg>
                                    <span class="text-sm">{{ auth()->user()->name }}</span>
                                </button>
                            </x-slot>
                            <x-slot name="content">
                                <x-dropdown-link :href="route('profile.edit')">Perfil</x-dropdown-link>
                                <form method="POST" action="{{ route('logout') }}">
                                    @csrf
                                    <x-dropdown-link :href="route('logout')" onclick="event.preventDefault(); this.closest('form').submit();">Cerrar sesión</x-dropdown-link>
                                </form>
                            </x-slot>
                        </x-dropdown>
                    </div>
                </div>
                <div class="sm:hidden mt-2 flex items-center gap-2">
                    <div class="flex-1 h-1.5 bg-slate-700 rounded-full overflow-hidden">
                        <div class="h-full bg-emerald-500 rounded-full" style="width: {{ $progressPercent }}%"></div>
                    </div>
                    <span class="text-xs text-slate-400">{{ $completedCount }}/{{ $totalLessons }}</span>
                </div>
            </div>
        </header>

        <div class="flex flex-col lg:flex-row bg-slate-950 min-h-[calc(100vh-8rem)]">
            {{-- Área del reproductor (video / PDF / imagen / enlace) --}}
            <main class="flex-1 flex flex-col min-w-0 lg:min-h-0">
                <div class="flex-1 flex flex-col p-4 lg:p-6">
                    <div class="rounded-xl overflow-hidden bg-slate-900 shadow-xl flex-1 flex flex-col min-h-[280px]">
                        <div class="p-4 border-b border-slate-800">
                            <h1 class="text-lg font-semibold text-white">{{ $lesson->title }}</h1>
                            @if($lesson->description)
                                <p class="text-sm text-slate-400 mt-1">{{ $lesson->description }}</p>
                            @endif
                        </div>
                        <div class="flex-1 flex items-center justify-center relative min-h-[300px]">
                            @if($lesson->isVideo())
                                <video
                                    id="lesson-player"
                                    class="w-full h-full max-h-[70vh] object-contain"
                                    controls
                                    controlsList="nodownload"
                                    src="{{ $lesson->resource_url }}"
                                    data-lesson-id="{{ $lesson->id }}"
                                    data-complete-url="{{ route('student.lessons.complete', $lesson) }}"
                                    data-next-url="{{ $nextLesson ? route('student.courses.lesson', [$course, $nextLesson]) : route('student.courses.show', $course) }}"
                                >
                                    Tu navegador no soporta video HTML5.
                                </video>
                            @elseif($lesson->isPdf() || $lesson->isDocument())
                                <iframe
                                    src="{{ $lesson->resource_url }}"
                                    class="w-full h-full min-h-[60vh] border-0"
                                    title="{{ $lesson->title }}"
                                ></iframe>
                            @elseif($lesson->isImage())
                                <img src="{{ $lesson->resource_url }}" alt="{{ $lesson->title }}" class="max-w-full max-h-[70vh] object-contain" />
                            @elseif($lesson->isLink())
                                <iframe
                                    src="{{ $lesson->resource_url }}"
                                    class="w-full h-full min-h-[60vh] border-0"
                                    title="{{ $lesson->title }}"
                                ></iframe>
                            @endif
                        </div>
                        {{-- Botón "Siguiente" y "Marcar como completada" para no-video --}}
                        @if(!$lesson->isVideo())
                            <div class="p-4 border-t border-slate-800 flex flex-wrap items-center justify-between gap-3">
                                <form action="{{ route('student.lessons.complete', $lesson) }}" method="POST" class="inline">
                                    @csrf
                                    <button type="submit" class="inline-flex items-center gap-2 px-4 py-2 rounded-lg bg-emerald-600 hover:bg-emerald-500 text-white text-sm font-medium transition-colors">
                                        <svg class="w-4 h-4" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M16.707 5.293a1 1 0 010 1.414l-8 8a1 1 0 01-1.414 0l-4-4a1 1 0 011.414-1.414L8 12.586l7.293-7.293a1 1 0 011.414 0z" clip-rule="evenodd"/></svg>
                                        Marcar como completada
                                    </button>
                                </form>
                                @if($nextLesson)
                                    <a href="{{ route('student.courses.lesson', [$course, $nextLesson]) }}" class="inline-flex items-center gap-2 px-4 py-2 rounded-lg bg-indigo-600 hover:bg-indigo-500 text-white text-sm font-medium transition-colors">
                                        Siguiente: {{ Str::limit($nextLesson->title, 30) }}
                                        <svg class="w-4 h-4" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M10.293 3.293a1 1 0 011.414 0l6 6a1 1 0 010 1.414l-6 6a1 1 0 01-1.414-1.414L14.586 11H3a1 1 0 110-2h11.586l-4.293-4.293a1 1 0 010-1.414z" clip-rule="evenodd"/></svg>
                                    </a>
                                @else
                                    <a href="{{ route('student.courses.show', $course) }}" class="inline-flex items-center gap-2 px-4 py-2 rounded-lg bg-slate-600 hover:bg-slate-500 text-white text-sm font-medium transition-colors">
                                        Volver al curso
                                    </a>
                                @endif
                            </div>
                        @endif
                    </div>
                </div>
            </main>

            {{-- Sidebar: Módulos / Clases --}}
            <aside class="w-full lg:w-80 xl:w-96 shrink-0 border-t lg:border-t-0 lg:border-l border-slate-800 bg-slate-900 overflow-y-auto max-h-[50vh] lg:max-h-none">
                <div class="p-4 border-b border-slate-800">
                    <h2 class="text-sm font-semibold text-slate-300 uppercase tracking-wider">Clases del curso</h2>
                </div>
                <nav class="p-2 space-y-0.5">
                    @foreach($lessons as $l)
                        @php $isCurrent = $l->id === $lesson->id; $isCompleted = in_array($l->id, $completedIds); @endphp
                        <a
                            href="{{ route('student.courses.lesson', [$course, $l]) }}"
                            class="flex items-center gap-3 px-3 py-2.5 rounded-lg text-left transition-colors {{ $isCurrent ? 'bg-indigo-600/80 text-white' : ($isCompleted ? 'text-slate-300 hover:bg-slate-800' : 'text-slate-400 hover:bg-slate-800 hover:text-slate-200') }}"
                        >
                            @if($isCompleted)
                                <span class="shrink-0 flex items-center justify-center w-6 h-6 rounded-full bg-emerald-500/20 text-emerald-400">
                                    <svg class="w-4 h-4" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M16.707 5.293a1 1 0 010 1.414l-8 8a1 1 0 01-1.414 0l-4-4a1 1 0 011.414-1.414L8 12.586l7.293-7.293a1 1 0 011.414 0z" clip-rule="evenodd"/></svg>
                                </span>
                            @else
                                <span class="shrink-0 flex items-center justify-center w-6 h-6 rounded-full {{ $isCurrent ? 'bg-white/20' : 'bg-slate-700 text-slate-500' }} text-xs font-medium">
                                    {{ $loop->iteration }}
                                </span>
                            @endif
                            <span class="flex-1 min-w-0 text-sm font-medium truncate">{{ $l->title }}</span>
                        </a>
                    @endforeach
                </nav>
            </aside>
        </div>
    </div>

    {{-- Video: al terminar, marcar completada y pasar a la siguiente --}}
    @if($lesson->isVideo())
    <script>
        (function() {
            var player = document.getElementById('lesson-player');
            if (!player) return;
            var completed = false;
            var completeUrl = player.dataset.completeUrl;
            var nextUrl = player.dataset.nextUrl;
            var token = document.querySelector('meta[name="csrf-token"]').getAttribute('content');

            function markCompleteAndNext() {
                if (completed) return;
                completed = true;
                var form = new FormData();
                form.append('_token', token);
                fetch(completeUrl, {
                    method: 'POST',
                    headers: {
                        'X-CSRF-TOKEN': token,
                        'Accept': 'application/json',
                        'X-Requested-With': 'XMLHttpRequest'
                    },
                    body: form
                }).then(function() {
                    window.location.href = nextUrl;
                }).catch(function() {
                    window.location.href = nextUrl;
                });
            }

            player.addEventListener('ended', markCompleteAndNext);
        })();
    </script>
    @endif
</x-app-layout>
