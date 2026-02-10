<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">
            {{ __('Gestión de Compras') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white dark:bg-gray-800 shadow-sm sm:rounded-lg">
                <div class="p-6">
                    <div class="overflow-x-auto">
                        <table class="min-w-full divide-y divide-gray-200 dark:divide-gray-700">
                            <thead>
                                <tr>
                                    <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-400 uppercase">ID</th>
                                    <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-400 uppercase">Estudiante</th>
                                    <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-400 uppercase">Curso</th>
                                    <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-400 uppercase">Monto</th>
                                    <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-400 uppercase">Estado</th>
                                    <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-400 uppercase">Fecha</th>
                                    <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-400 uppercase">Acciones</th>
                                </tr>
                            </thead>
                            <tbody class="divide-y divide-gray-200 dark:divide-gray-700">
                                @forelse($purchases as $purchase)
                                <tr>
                                    <td class="px-4 py-3 text-sm">#{{ $purchase->id }}</td>
                                    <td class="px-4 py-3 text-sm">{{ $purchase->user->name }}</td>
                                    <td class="px-4 py-3 text-sm">{{ $purchase->course->title }}</td>
                                    <td class="px-4 py-3 text-sm">${{ number_format($purchase->amount, 2) }}</td>
                                    <td class="px-4 py-3 text-sm">
                                        <span class="px-2 py-1 text-xs rounded-full 
                                            {{ $purchase->status === 'approved' ? 'bg-green-100 text-green-800 dark:bg-green-900 dark:text-green-200' : 
                                               ($purchase->status === 'pending' ? 'bg-yellow-100 text-yellow-800 dark:bg-yellow-900 dark:text-yellow-200' : 
                                               'bg-red-100 text-red-800 dark:bg-red-900 dark:text-red-200') }}">
                                            {{ ucfirst($purchase->status) }}
                                        </span>
                                    </td>
                                    <td class="px-4 py-3 text-sm">{{ $purchase->created_at->format('d/m/Y H:i') }}</td>
                                    <td class="px-4 py-3 text-sm">
                                        @if($purchase->status === 'pending')
                                        <div class="flex gap-2">
                                            <form action="{{ route('admin.purchases.approve', $purchase) }}" method="POST" class="inline">
                                                @csrf
                                                <button type="submit" 
                                                        class="bg-green-500 hover:bg-green-600 text-white px-3 py-1 rounded text-xs">
                                                    Aprobar
                                                </button>
                                            </form>
                                            <form action="{{ route('admin.purchases.reject', $purchase) }}" method="POST" class="inline">
                                                @csrf
                                                <button type="submit" 
                                                        class="bg-red-500 hover:bg-red-600 text-white px-3 py-1 rounded text-xs">
                                                    Rechazar
                                                </button>
                                            </form>
                                        </div>
                                        @else
                                        <span class="text-gray-400 text-xs">-</span>
                                        @endif
                                    </td>
                                </tr>
                                @empty
                                <tr>
                                    <td colspan="7" class="px-4 py-3 text-sm text-center text-gray-500">No hay compras registradas</td>
                                </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>

                    <div class="mt-4">
                        {{ $purchases->links() }}
                    </div>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>

