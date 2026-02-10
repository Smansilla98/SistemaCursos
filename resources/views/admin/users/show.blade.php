<x-app-layout>
    <x-slot name="header">
        <div class="flex justify-between items-center">
            <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">
                {{ $user->name }}
            </h2>
            <div class="flex gap-2">
                <a href="{{ route('admin.users.edit', $user) }}" class="bg-yellow-500 hover:bg-yellow-600 text-white px-4 py-2 rounded-lg">
                    Editar
                </a>
                <a href="{{ route('admin.users.index') }}" class="bg-gray-500 hover:bg-gray-600 text-white px-4 py-2 rounded-lg">
                    Volver
                </a>
            </div>
        </div>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                <!-- Información del Usuario -->
                <div class="bg-white dark:bg-gray-800 shadow-sm sm:rounded-lg">
                    <div class="p-6">
                        <h3 class="text-lg font-semibold mb-4">Información Personal</h3>
                        <div class="space-y-2">
                            <p><strong>Nombre:</strong> {{ $user->name }}</p>
                            <p><strong>Email:</strong> {{ $user->email }}</p>
                            <p><strong>Rol:</strong> 
                                <span class="px-2 py-1 text-xs rounded-full {{ $user->hasRole('admin') ? 'bg-purple-100 text-purple-800' : 'bg-blue-100 text-blue-800' }}">
                                    {{ $user->roles->first()->name ?? 'Sin rol' }}
                                </span>
                            </p>
                            <p><strong>Registrado:</strong> {{ $user->created_at->format('d/m/Y') }}</p>
                        </div>
                    </div>
                </div>

                <!-- Estadísticas -->
                <div class="bg-white dark:bg-gray-800 shadow-sm sm:rounded-lg">
                    <div class="p-6">
                        <h3 class="text-lg font-semibold mb-4">Estadísticas</h3>
                        <div class="space-y-2">
                            <p><strong>Cursos Comprados:</strong> {{ $user->courses()->count() }}</p>
                            <p><strong>Compras Totales:</strong> {{ $user->purchases()->count() }}</p>
                            <p><strong>Compras Aprobadas:</strong> {{ $user->purchases()->where('status', 'approved')->count() }}</p>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Compras del Usuario -->
            <div class="bg-white dark:bg-gray-800 shadow-sm sm:rounded-lg mt-6">
                <div class="p-6">
                    <h3 class="text-lg font-semibold mb-4">Compras</h3>
                    <div class="overflow-x-auto">
                        <table class="min-w-full divide-y divide-gray-200 dark:divide-gray-700">
                            <thead>
                                <tr>
                                    <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-400 uppercase">Curso</th>
                                    <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-400 uppercase">Monto</th>
                                    <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-400 uppercase">Estado</th>
                                    <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-400 uppercase">Fecha</th>
                                </tr>
                            </thead>
                            <tbody class="divide-y divide-gray-200 dark:divide-gray-700">
                                @forelse($user->purchases as $purchase)
                                <tr>
                                    <td class="px-4 py-3 text-sm">{{ $purchase->course->title }}</td>
                                    <td class="px-4 py-3 text-sm">${{ number_format($purchase->amount, 2) }}</td>
                                    <td class="px-4 py-3 text-sm">
                                        <span class="px-2 py-1 text-xs rounded-full 
                                            {{ $purchase->status === 'approved' ? 'bg-green-100 text-green-800' : 
                                               ($purchase->status === 'pending' ? 'bg-yellow-100 text-yellow-800' : 
                                               'bg-red-100 text-red-800') }}">
                                            {{ ucfirst($purchase->status) }}
                                        </span>
                                    </td>
                                    <td class="px-4 py-3 text-sm">{{ $purchase->created_at->format('d/m/Y') }}</td>
                                </tr>
                                @empty
                                <tr>
                                    <td colspan="4" class="px-4 py-3 text-sm text-center text-gray-500">No hay compras registradas</td>
                                </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>

