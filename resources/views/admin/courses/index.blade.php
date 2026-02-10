<x-app-layout>
    <x-slot name="header">
        <div class="flex justify-between items-center">
            <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">
                {{ __('Gestión de Cursos') }}
            </h2>
            <a href="{{ route('admin.courses.create') }}" 
               class="bg-blue-500 hover:bg-blue-600 text-white px-4 py-2 rounded-lg">
                Crear Curso
            </a>
        </div>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white dark:bg-gray-800 shadow-sm sm:rounded-lg">
                <div class="p-6">
                    <div class="overflow-x-auto">
                        <table class="min-w-full divide-y divide-gray-200 dark:divide-gray-700">
                            <thead>
                                <tr>
                                    <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-400 uppercase">Título</th>
                                    <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-400 uppercase">Precio</th>
                                    <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-400 uppercase">Estado</th>
                                    <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-400 uppercase">Lecciones</th>
                                    <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-400 uppercase">Acciones</th>
                                </tr>
                            </thead>
                            <tbody class="divide-y divide-gray-200 dark:divide-gray-700">
                                @forelse($courses as $course)
                                <tr>
                                    <td class="px-4 py-3">
                                        <div class="flex items-center gap-3">
                                            @if($course->thumbnail)
                                            <img src="{{ asset('storage/' . $course->thumbnail) }}" 
                                                 alt="{{ $course->title }}" 
                                                 class="w-16 h-16 object-cover rounded">
                                            @endif
                                            <span class="font-medium">{{ $course->title }}</span>
                                        </div>
                                    </td>
                                    <td class="px-4 py-3 text-sm">${{ number_format($course->price, 2) }}</td>
                                    <td class="px-4 py-3 text-sm">
                                        <span class="px-2 py-1 text-xs rounded-full {{ $course->is_active ? 'bg-green-100 text-green-800' : 'bg-gray-100 text-gray-800' }}">
                                            {{ $course->is_active ? 'Activo' : 'Inactivo' }}
                                        </span>
                                    </td>
                                    <td class="px-4 py-3 text-sm">{{ $course->lessons()->count() }}</td>
                                    <td class="px-4 py-3 text-sm">
                                        <div class="flex gap-2">
                                            <a href="{{ route('admin.courses.show', $course) }}" 
                                               class="text-blue-500 hover:text-blue-600">Ver</a>
                                            <a href="{{ route('admin.courses.edit', $course) }}" 
                                               class="text-yellow-500 hover:text-yellow-600">Editar</a>
                                            <form action="{{ route('admin.courses.destroy', $course) }}" 
                                                  method="POST" 
                                                  class="inline"
                                                  onsubmit="return confirm('¿Estás seguro de eliminar este curso?')">
                                                @csrf
                                                @method('DELETE')
                                                <button type="submit" class="text-red-500 hover:text-red-600">Eliminar</button>
                                            </form>
                                        </div>
                                    </td>
                                </tr>
                                @empty
                                <tr>
                                    <td colspan="5" class="px-4 py-3 text-sm text-center text-gray-500">No hay cursos registrados</td>
                                </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>

                    <div class="mt-4">
                        {{ $courses->links() }}
                    </div>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>

