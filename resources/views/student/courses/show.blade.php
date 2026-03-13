<x-app-layout>
    <x-slot name="header">
        <h1 class="text-2xl font-bold text-slate-800 tracking-tight">{{ $course->title }}</h1>
        <p class="mt-1 text-sm text-slate-500">Contenido del curso</p>
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
                    </div>
                </div>
            </div>

            @if($hasAccess)
            <div class="card-nova overflow-hidden">
                <div class="p-6 border-b border-slate-200">
                    <h3 class="text-lg font-semibold text-slate-800">Contenido del curso</h3>
                </div>
                <div class="divide-y divide-slate-200">
                    @forelse($course->lessons()->orderBy('order')->get() as $lesson)
                    <div class="p-6 flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4">
                        <div class="flex items-center gap-4">
                            <span class="text-slate-500 font-semibold">#{{ $lesson->order }}</span>
                            <div>
                                <h4 class="font-semibold text-slate-800">{{ $lesson->title }}</h4>
                                @if($lesson->description)
                                    <p class="text-sm text-slate-600 mt-1">{{ $lesson->description }}</p>
                                @endif
                                <p class="text-sm text-slate-500 mt-1">{{ \App\Models\Lesson::typeLabels()[$lesson->file_type] ?? $lesson->file_type }}</p>
                            </div>
                        </div>
                        <div class="shrink-0">
                            @if($lesson->isVideo())
                            <a href="{{ $lesson->resource_url }}" target="_blank" rel="noopener" class="inline-flex items-center gap-2 rounded-lg px-4 py-2 text-sm font-medium text-white bg-red-600 hover:bg-red-700">
                                <svg class="w-5 h-5" fill="currentColor" viewBox="0 0 20 20"><path d="M6.3 2.841A1.5 1.5 0 004 4.11V15.89a1.5 1.5 0 002.3 1.269l9.344-5.89a1.5 1.5 0 000-2.538L6.3 2.84z"/></svg>
                                Ver video
                            </a>
                            @elseif($lesson->isPdf())
                            <a href="{{ $lesson->resource_url }}" target="_blank" rel="noopener" class="inline-flex items-center gap-2 rounded-lg px-4 py-2 text-sm font-medium text-white bg-slate-700 hover:bg-slate-800">
                                <svg class="w-5 h-5" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M4 4a2 2 0 012-2h4.586A2 2 0 0112 2.586L15.414 6A2 2 0 0116 7.414V16a2 2 0 01-2 2H6a2 2 0 01-2-2V4z" clip-rule="evenodd"/></svg>
                                Ver PDF
                            </a>
                            @elseif($lesson->isDocument())
                            <a href="{{ $lesson->resource_url }}" target="_blank" rel="noopener" class="inline-flex items-center gap-2 rounded-lg px-4 py-2 text-sm font-medium text-white bg-amber-600 hover:bg-amber-700">
                                <svg class="w-5 h-5" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M4 4a2 2 0 012-2h4.586A2 2 0 0112 2.586L15.414 6A2 2 0 0116 7.414V16a2 2 0 01-2 2H6a2 2 0 01-2-2V4z" clip-rule="evenodd"/></svg>
                                Ver documento
                            </a>
                            @elseif($lesson->isImage())
                            <a href="{{ $lesson->resource_url }}" target="_blank" rel="noopener" class="inline-flex items-center gap-2 rounded-lg px-4 py-2 text-sm font-medium text-white bg-emerald-600 hover:bg-emerald-700">
                                <svg class="w-5 h-5" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M4 3a2 2 0 00-2 2v10a2 2 0 002 2h12a2 2 0 002-2V5a2 2 0 00-2-2H4zm12 12H4l4-8 3 6 2-4 3 6z" clip-rule="evenodd"/></svg>
                                Ver imagen
                            </a>
                            @else
                            <a href="{{ $lesson->resource_url }}" target="_blank" rel="noopener" class="inline-flex items-center gap-2 rounded-lg px-4 py-2 text-sm font-medium text-white bg-indigo-600 hover:bg-indigo-700">
                                <svg class="w-5 h-5" fill="currentColor" viewBox="0 0 20 20"><path d="M11 3a1 1 0 100 2h2.586l-6.293 6.293a1 1 0 101.414 1.414L15 6.414V9a1 1 0 102 0V4a1 1 0 00-1-1h-5z"/><path d="M5 5a2 2 0 00-2 2v8a2 2 0 002 2h8a2 2 0 002-2v-3a1 1 0 10-2 0v3H5V7h3a1 1 0 000-2H5z"/></svg>
                                Abrir enlace
                            </a>
                            @endif
                        </div>
                    </div>
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
