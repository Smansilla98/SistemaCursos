<x-app-layout>
    <x-slot name="header">
        <div class="flex flex-col sm:flex-row sm:justify-between sm:items-start gap-4">
            <div>
                <h1 class="text-2xl font-bold text-slate-800 tracking-tight">{{ $course->title }}</h1>
                <p class="mt-1 text-sm text-slate-500">Contenido del curso</p>
            </div>
            @if($hasAccess && $course->lessons->isNotEmpty() && $firstLessonUrl)
                <a href="{{ $nextLessonUrl ?? $firstLessonUrl }}" class="btn-nova shrink-0">
                    {{ $nextLessonUrl ? 'Continuar donde lo dejaste' : 'Entrar al curso' }}
                </a>
            @endif
        </div>
    </x-slot>

    <div class="py-8">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8 space-y-6">
            @if(!$hasAccess)
            <div class="card-nova p-6">
                <h2 class="text-xl font-semibold text-slate-800 mb-4">Acceso al curso</h2>
                <div class="mb-6">
                    <p class="mb-4">
                        Precio: <span class="text-3xl font-bold text-indigo-600">${{ number_format($course->price, 2) }}</span>
                    </p>
                    <form action="{{ route('student.courses.purchase', $course) }}" method="POST" class="inline">
                        @csrf
                        <button type="submit" class="btn-nova text-base px-8 py-3">Comprar con MercadoPago</button>
                    </form>
                </div>
                <div class="border-t border-slate-200 pt-6">
                    <p class="text-sm text-slate-600">Al comprar, tendrás acceso a todo el contenido una vez aprobado el pago.</p>
                </div>
            </div>
            @endif

            <div class="card-nova p-6">
                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    <div>
                        @if($course->thumbnail)
                        <img src="{{ asset('storage/' . $course->thumbnail) }}" alt="{{ $course->title }}" class="w-full h-64 object-cover rounded-xl">
                        @else
                        <div class="w-full h-64 bg-gradient-to-br from-indigo-500 to-indigo-700 rounded-xl flex items-center justify-center">
                            <span class="text-white text-4xl font-bold">{{ substr($course->title, 0, 2) }}</span>
                        </div>
                        @endif
                    </div>
                    <div>
                        <h2 class="text-2xl font-bold text-slate-800 mb-4">{{ $course->title }}</h2>
                        <p class="text-slate-600 whitespace-pre-line mb-4">{{ $course->description }}</p>
                        <p class="text-2xl font-bold text-indigo-600">${{ number_format($course->price, 2) }}</p>
                        @if($hasAccess && $course->lessons->isNotEmpty())
                            @php
                                $total = $course->lessons->count();
                                $completed = (int) ($completedCount ?? 0);
                                $pct = $total > 0 ? (int) round(($completed / $total) * 100) : 0;
                            @endphp
                            <div class="mt-4">
                                <div class="flex items-center justify-between text-sm text-slate-600 mb-1">
                                    <span>Tu progreso</span>
                                    <span>{{ $completed }} de {{ $total }} clases ({{ $pct }}%)</span>
                                </div>
                                <div class="h-2 bg-slate-200 rounded-full overflow-hidden">
                                    <div class="h-full bg-emerald-500 rounded-full transition-all" style="width: {{ $pct }}%"></div>
                                </div>
                            </div>
                        @endif
                    </div>
                </div>
            </div>

            @if($hasAccess)
            <div class="card-nova overflow-hidden">
                <div class="p-6 border-b border-slate-200">
                    <h3 class="text-lg font-semibold text-slate-800">Contenido del curso</h3>
                    <p class="text-sm text-slate-500 mt-1">Entrá a cada clase para ver el material (video, PDF o documento) y que se marque tu avance.</p>
                </div>
                <div class="divide-y divide-slate-200">
                    @forelse($course->lessons as $l)
                    @php
                        $completed = in_array($l->id, $completedLessonIds ?? []);
                    @endphp
                    <a href="{{ route('student.courses.lesson', [$course, $l]) }}" class="p-6 flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4 hover:bg-slate-50 transition-colors group">
                        <div class="flex items-center gap-4">
                            @if($completed)
                                <span class="shrink-0 flex items-center justify-center w-10 h-10 rounded-full bg-emerald-100 text-emerald-600">
                                    <svg class="w-5 h-5" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M16.707 5.293a1 1 0 010 1.414l-8 8a1 1 0 01-1.414 0l-4-4a1 1 0 011.414-1.414L8 12.586l7.293-7.293a1 1 0 011.414 0z" clip-rule="evenodd"/></svg>
                                </span>
                            @else
                                <span class="shrink-0 flex items-center justify-center w-10 h-10 rounded-full bg-slate-200 text-slate-600 text-sm font-semibold group-hover:bg-indigo-100 group-hover:text-indigo-700 transition-colors">{{ $loop->iteration }}</span>
                            @endif
                            <div>
                                <h4 class="font-semibold text-slate-800 group-hover:text-indigo-700 transition-colors">{{ $l->title }}</h4>
                                @if($l->description)
                                    <p class="text-sm text-slate-600 mt-0.5">{{ Str::limit($l->description, 80) }}</p>
                                @endif
                                <p class="text-sm text-slate-500 mt-1">{{ \App\Models\Lesson::typeLabels()[$l->file_type] ?? $l->file_type }}</p>
                            </div>
                        </div>
                        <span class="text-sm font-medium text-indigo-600 group-hover:text-indigo-700 shrink-0 flex items-center gap-1">
                            {{ $completed ? 'Ver de nuevo' : 'Ver clase' }}
                            <svg class="w-4 h-4" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M7.293 14.707a1 1 0 001.414 0l10-10a1 1 0 00-1.414-1.414L8 12.586 2.707 3.293a1 1 0 000 1.414z" clip-rule="evenodd"/></svg>
                        </span>
                    </a>
                    @empty
                    <div class="p-12 text-center text-slate-500">Este curso aún no tiene contenido disponible.</div>
                    @endforelse
                </div>
            </div>
            @else
            <div class="card-nova p-6">
                <h3 class="text-lg font-semibold text-slate-800 mb-2">Contenido del curso</h3>
                <p class="text-slate-500 text-center py-6">Comprá este curso para acceder a todo el contenido.</p>
                <p class="text-center text-sm text-slate-400">Este curso tiene {{ $course->lessons()->count() }} lecciones.</p>
            </div>
            @endif
        </div>
    </div>
</x-app-layout>
