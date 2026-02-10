<x-app-layout>
    <x-slot name="header">
        <div class="flex justify-between items-center">
            <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">
                {{ __('Gestión de Usuarios') }}
            </h2>
            <a href="{{ route('admin.users.create') }}" 
               class="bg-blue-500 hover:bg-blue-600 text-white px-4 py-2 rounded-lg">
                Crear Usuario
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
                                    <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-400 uppercase">Nombre</th>
                                    <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-400 uppercase">Email</th>
                                    <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-400 uppercase">Rol</th>
                                    <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-400 uppercase">Cursos</th>
                                    <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-400 uppercase">Acciones</th>
                                </tr>
                            </thead>
                            <tbody class="divide-y divide-gray-200 dark:divide-gray-700">
                                @forelse($users as $user)
                                <tr>
                                    <td class="px-4 py-3 text-sm">{{ $user->name }}</td>
                                    <td class="px-4 py-3 text-sm">{{ $user->email }}</td>
                                    <td class="px-4 py-3 text-sm">
                                        <span class="px-2 py-1 text-xs rounded-full {{ $user->hasRole('admin') ? 'bg-purple-100 text-purple-800' : 'bg-blue-100 text-blue-800' }}">
                                            {{ $user->roles->first()->name ?? 'Sin rol' }}
                                        </span>
                                    </td>
                                    <td class="px-4 py-3 text-sm">{{ $user->courses()->count() }}</td>
                                    <td class="px-4 py-3 text-sm">
                                        <div class="flex gap-2">
                                            <a href="{{ route('admin.users.show', $user) }}" 
                                               class="text-blue-500 hover:text-blue-600">Ver</a>
                                            <a href="{{ route('admin.users.edit', $user) }}" 
                                               class="text-yellow-500 hover:text-yellow-600">Editar</a>
                                            <form action="{{ route('admin.users.destroy', $user) }}" 
                                                  method="POST" 
                                                  class="inline"
                                                  onsubmit="return confirm('¿Estás seguro de eliminar este usuario?')">
                                                @csrf
                                                @method('DELETE')
                                                <button type="submit" class="text-red-500 hover:text-red-600">Eliminar</button>
                                            </form>
                                        </div>
                                    </td>
                                </tr>
                                @empty
                                <tr>
                                    <td colspan="5" class="px-4 py-3 text-sm text-center text-gray-500">No hay usuarios registrados</td>
                                </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>

                    <div class="mt-4">
                        {{ $users->links() }}
                    </div>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>

