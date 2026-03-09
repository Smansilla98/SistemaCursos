<x-app-layout>
    <x-slot name="header">
        <div class="flex flex-col sm:flex-row sm:justify-between sm:items-center gap-4">
            <div>
                <h1 class="text-2xl font-bold text-slate-800 tracking-tight">Cursos</h1>
                <p class="mt-1 text-sm text-slate-500">Gestión de cursos</p>
            </div>
            <a href="{{ route('admin.courses.create') }}" class="btn-nova shrink-0">Crear curso</a>
        </div>
    </x-slot>

    <div class="py-8">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="card-nova overflow-hidden">
                <div class="overflow-x-auto">
                    <table class="min-w-full divide-y divide-slate-200">
                        <thead class="bg-slate-50">
                            <tr>
                                <th class="px-4 py-3 text-left text-xs font-medium text-slate-500 uppercase tracking-wider">Título</th>
                                <th class="px-4 py-3 text-left text-xs font-medium text-slate-500 uppercase tracking-wider">Precio</th>
                                <th class="px-4 py-3 text-left text-xs font-medium text-slate-500 uppercase tracking-wider">Estado</th>
                                <th class="px-4 py-3 text-left text-xs font-medium text-slate-500 uppercase tracking-wider">Lecciones</th>
                                <th class="px-4 py-3 text-left text-xs font-medium text-slate-500 uppercase tracking-wider">Acciones</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-slate-200">
                            @forelse($courses as $course)
                            <tr class="hover:bg-slate-50">
                                <td class="px-4 py-3">
                                    <div class="flex items-center gap-3">
                                        @if($course->thumbnail)
                                        <img src="{{ asset('storage/' . $course->thumbnail) }}" alt="{{ $course->title }}" class="w-12 h-12 object-cover rounded-lg">
                                        @endif
                                        <span class="font-medium text-slate-800">{{ $course->title }}</span>
                                    </div>
                                </td>
                                <td class="px-4 py-3 text-sm text-slate-800">${{ number_format($course->price, 2) }}</td>
                                <td class="px-4 py-3">
                                    <span class="inline-flex px-2.5 py-0.5 rounded-full text-xs font-medium {{ $course->is_active ? 'bg-emerald-100 text-emerald-800' : 'bg-slate-100 text-slate-700' }}">
                                        {{ $course->is_active ? 'Activo' : 'Inactivo' }}
                                    </span>
                                </td>
                                <td class="px-4 py-3 text-sm text-slate-600">{{ $course->lessons()->count() }}</td>
                                <td class="px-4 py-3">
                                    <div class="flex gap-3 text-sm font-medium">
                                        <a href="{{ route('admin.courses.show', $course) }}" class="text-indigo-600 hover:text-indigo-700">Ver</a>
                                        <a href="{{ route('admin.courses.edit', $course) }}" class="text-slate-600 hover:text-slate-800">Editar</a>
                                        <form action="{{ route('admin.courses.destroy', $course) }}" method="POST" class="inline" onsubmit="return confirm('¿Eliminar este curso?')">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit" class="text-red-600 hover:text-red-700">Eliminar</button>
                                        </form>
                                    </div>
                                </td>
                            </tr>
                            @empty
                            <tr>
                                <td colspan="5" class="px-4 py-12 text-center text-slate-500">No hay cursos registrados</td>
                            </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
                @if($courses->hasPages())
                <div class="px-4 py-3 border-t border-slate-200">{{ $courses->links() }}</div>
                @endif
            </div>
        </div>
    </div>
</x-app-layout>
