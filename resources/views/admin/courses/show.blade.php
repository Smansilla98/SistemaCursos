<x-app-layout>
    <x-slot name="header">
        <div class="flex flex-col sm:flex-row sm:justify-between sm:items-start gap-4">
            <div>
                <h1 class="text-2xl font-bold text-slate-800 tracking-tight">{{ $course->title }}</h1>
                <p class="mt-1 text-sm text-slate-500">Detalle y lecciones</p>
            </div>
            <div class="flex gap-2 shrink-0">
                <a href="{{ route('admin.courses.edit', $course) }}" class="btn-nova">Editar</a>
                <a href="{{ route('admin.courses.index') }}" class="inline-flex items-center justify-center rounded-lg px-4 py-2 text-sm font-medium text-slate-700 bg-white border border-slate-300 shadow-sm hover:bg-slate-50">Volver</a>
            </div>
        </div>
    </x-slot>

    <div class="py-8">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8 space-y-6">
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
                        <h2 class="text-xl font-bold text-slate-800 mb-4">{{ $course->title }}</h2>
                        <p class="text-slate-600 whitespace-pre-line mb-4">{{ $course->description }}</p>
                        <div class="space-y-2 text-sm">
                            <p><span class="font-medium text-slate-700">Precio:</span> ${{ number_format($course->price, 2) }}</p>
                            <p>
                                <span class="font-medium text-slate-700">Estado:</span>
                                <span class="inline-flex px-2.5 py-0.5 rounded-full text-xs font-medium {{ $course->is_active ? 'bg-emerald-100 text-emerald-800' : 'bg-slate-100 text-slate-700' }}">{{ $course->is_active ? 'Activo' : 'Inactivo' }}</span>
                            </p>
                            <p><span class="font-medium text-slate-700">Lecciones:</span> {{ $course->lessons()->count() }}</p>
                        </div>
                    </div>
                </div>
            </div>

            <div class="card-nova overflow-hidden">
                <div class="p-6 border-b border-slate-200">
                    <h3 class="text-lg font-semibold text-slate-800">Lecciones del curso</h3>
                </div>

                <div class="p-6">
                    <div class="mb-6 p-4 rounded-xl bg-slate-50 border border-slate-200">
                        <h4 class="font-semibold text-slate-800 mb-3">Agregar lección</h4>
                        <form action="{{ route('admin.courses.lessons.add', $course) }}" method="POST" enctype="multipart/form-data">
                            @csrf
                            <div class="grid grid-cols-1 md:grid-cols-4 gap-4">
                                <div>
                                    <x-input-label for="lesson_title" :value="__('Título')" />
                                    <x-text-input id="lesson_title" name="title" type="text" class="mt-1 block w-full" required />
                                </div>
                                <div>
                                    <x-input-label for="lesson_order" :value="__('Orden')" />
                                    <x-text-input id="lesson_order" name="order" type="number" min="0" class="mt-1 block w-full" required />
                                </div>
                                <div>
                                    <x-input-label for="lesson_file_type" :value="__('Tipo')" />
                                    <select id="lesson_file_type" name="file_type" class="input-nova mt-1 block w-full rounded-lg border-slate-300" required>
                                        <option value="video">Video</option>
                                        <option value="pdf">PDF</option>
                                    </select>
                                </div>
                                <div>
                                    <x-input-label for="lesson_file" :value="__('Archivo')" />
                                    <input id="lesson_file" name="file" type="file" class="mt-1 block w-full text-sm text-slate-500 file:mr-2 file:py-2 file:px-3 file:rounded-lg file:border-0 file:text-sm file:font-medium file:bg-indigo-50 file:text-indigo-700" required />
                                </div>
                            </div>
                            <div class="mt-4">
                                <x-primary-button>Agregar lección</x-primary-button>
                            </div>
                        </form>
                    </div>

                    <div class="space-y-3">
                        @forelse($course->lessons()->orderBy('order')->get() as $lesson)
                        <div class="flex items-center justify-between p-4 rounded-xl bg-slate-50 border border-slate-200">
                            <div class="flex items-center gap-4">
                                <span class="text-slate-500 font-medium">#{{ $lesson->order }}</span>
                                <div>
                                    <h5 class="font-semibold text-slate-800">{{ $lesson->title }}</h5>
                                    <p class="text-sm text-slate-500">{{ $lesson->file_type }} · {{ $lesson->is_locked ? 'Bloqueado' : 'Desbloqueado' }}</p>
                                </div>
                            </div>
                            <form action="{{ route('admin.lessons.delete', $lesson) }}" method="POST" class="inline" onsubmit="return confirm('¿Eliminar esta lección?')">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="text-sm font-medium text-red-600 hover:text-red-700">Eliminar</button>
                            </form>
                        </div>
                        @empty
                        <p class="text-center py-8 text-slate-500">No hay lecciones en este curso</p>
                        @endforelse
                    </div>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
