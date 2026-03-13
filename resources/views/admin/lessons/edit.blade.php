<x-app-layout>
    <x-slot name="header">
        <div class="flex flex-col sm:flex-row sm:justify-between sm:items-start gap-4">
            <div>
                <h1 class="text-2xl font-bold text-slate-800 tracking-tight">Editar lección</h1>
                <p class="mt-1 text-sm text-slate-500">Curso: {{ $lesson->course->title }}</p>
            </div>
            <a href="{{ route('admin.courses.show', $lesson->course) }}" class="inline-flex items-center justify-center rounded-lg px-4 py-2 text-sm font-medium text-slate-700 bg-white border border-slate-300 shadow-sm hover:bg-slate-50">Volver al curso</a>
        </div>
    </x-slot>

    <div class="py-8">
        <div class="max-w-3xl mx-auto sm:px-6 lg:px-8">
            <div class="card-nova p-6">
                <form action="{{ route('admin.lessons.update', $lesson) }}" method="POST" enctype="multipart/form-data" class="space-y-6" id="form-lesson">
                    @csrf
                    @method('PUT')

                    <div>
                        <x-input-label for="title" :value="__('Título de la lección')" />
                        <x-text-input id="title" name="title" type="text" class="mt-1 block w-full" :value="old('title', $lesson->title)" required />
                        <x-input-error class="mt-2" :messages="$errors->get('title')" />
                    </div>

                    <div>
                        <x-input-label for="description" :value="__('Descripción (opcional)')" />
                        <textarea id="description" name="description" rows="3" class="input-nova mt-1 block w-full rounded-lg border-slate-300 text-slate-800 focus:border-indigo-500 focus:ring-indigo-500">{{ old('description', $lesson->description) }}</textarea>
                        <x-input-error class="mt-2" :messages="$errors->get('description')" />
                    </div>

                    <div>
                        <x-input-label for="order" :value="__('Orden')" />
                        <x-text-input id="order" name="order" type="number" min="0" class="mt-1 block w-full" :value="old('order', $lesson->order)" required />
                        <x-input-error class="mt-2" :messages="$errors->get('order')" />
                    </div>

                    <div>
                        <x-input-label for="file_type" :value="__('Tipo de material')" />
                        <select id="file_type" name="file_type" class="input-nova mt-1 block w-full rounded-lg border-slate-300" required>
                            @foreach(\App\Models\Lesson::typeLabels() as $value => $label)
                                <option value="{{ $value }}" {{ old('file_type', $lesson->file_type) === $value ? 'selected' : '' }}>{{ $label }}</option>
                            @endforeach
                        </select>
                        <x-input-error class="mt-2" :messages="$errors->get('file_type')" />
                    </div>

                    {{-- Enlace URL (solo si tipo = link) --}}
                    <div id="wrap-url" class="{{ $lesson->file_type !== 'link' ? 'hidden' : '' }}">
                        <x-input-label for="url" :value="__('URL del enlace')" />
                        <x-text-input id="url" name="url" type="url" class="mt-1 block w-full" :value="old('url', $lesson->isLink() ? $lesson->file_path : '')" placeholder="https://..." />
                        <x-input-error class="mt-2" :messages="$errors->get('url')" />
                    </div>

                    {{-- Archivo (si tipo es video, pdf, document, image) --}}
                    <div id="wrap-file" class="{{ $lesson->file_type === 'link' ? 'hidden' : '' }}">
                        <x-input-label for="file" :value="__('Archivo')" />
                        @if($lesson->file_path && !$lesson->isLink())
                            <p class="text-sm text-slate-500 mt-1 mb-2">Actual: {{ basename($lesson->file_path) }}. Dejá vacío para mantenerlo.</p>
                        @endif
                        <input id="file" name="file" type="file" class="mt-1 block w-full text-sm text-slate-500 file:mr-2 file:py-2 file:px-3 file:rounded-lg file:border-0 file:text-sm file:font-medium file:bg-indigo-50 file:text-indigo-700" accept=".mp4,.webm,.mov,.pdf,.doc,.docx,.odt,.txt,.jpg,.jpeg,.png,.gif,.webp" />
                        <x-input-error class="mt-2" :messages="$errors->get('file')" />
                    </div>

                    <div class="flex items-center">
                        <input id="is_locked" name="is_locked" type="checkbox" value="1" class="rounded border-slate-300 text-indigo-600 shadow-sm focus:ring-indigo-500" {{ old('is_locked', $lesson->is_locked) ? 'checked' : '' }} />
                        <x-input-label for="is_locked" :value="__('Lección bloqueada (solo visible tras compra)')" class="ms-2" />
                    </div>

                    <div class="flex items-center gap-4 pt-2">
                        <x-primary-button>Guardar cambios</x-primary-button>
                        <a href="{{ route('admin.courses.show', $lesson->course) }}" class="text-sm font-medium text-slate-600 hover:text-slate-800">Cancelar</a>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <script>
        document.getElementById('file_type').addEventListener('change', function() {
            var isLink = this.value === 'link';
            document.getElementById('wrap-url').classList.toggle('hidden', !isLink);
            document.getElementById('wrap-file').classList.toggle('hidden', isLink);
            document.getElementById('file').required = !isLink;
            document.getElementById('url').required = isLink;
        });
        document.getElementById('file').required = document.getElementById('file_type').value !== 'link';
        document.getElementById('url').required = document.getElementById('file_type').value === 'link';
    </script>
</x-app-layout>
