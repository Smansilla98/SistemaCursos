<x-app-layout>
    <x-slot name="header">
        <h1 class="text-2xl font-bold text-slate-800 tracking-tight">Compras</h1>
        <p class="mt-1 text-sm text-slate-500">Gestión de compras</p>
    </x-slot>

    <div class="py-8">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="card-nova overflow-hidden">
                <div class="overflow-x-auto">
                    <table class="min-w-full divide-y divide-slate-200">
                        <thead class="bg-slate-50">
                            <tr>
                                <th class="px-4 py-3 text-left text-xs font-medium text-slate-500 uppercase tracking-wider">ID</th>
                                <th class="px-4 py-3 text-left text-xs font-medium text-slate-500 uppercase tracking-wider">Estudiante</th>
                                <th class="px-4 py-3 text-left text-xs font-medium text-slate-500 uppercase tracking-wider">Curso</th>
                                <th class="px-4 py-3 text-left text-xs font-medium text-slate-500 uppercase tracking-wider">Monto</th>
                                <th class="px-4 py-3 text-left text-xs font-medium text-slate-500 uppercase tracking-wider">Estado</th>
                                <th class="px-4 py-3 text-left text-xs font-medium text-slate-500 uppercase tracking-wider">Fecha</th>
                                <th class="px-4 py-3 text-left text-xs font-medium text-slate-500 uppercase tracking-wider">Acciones</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-slate-200">
                            @forelse($purchases as $purchase)
                            <tr class="hover:bg-slate-50">
                                <td class="px-4 py-3 text-sm text-slate-600">#{{ $purchase->id }}</td>
                                <td class="px-4 py-3 text-sm font-medium text-slate-800">{{ $purchase->user->name }}</td>
                                <td class="px-4 py-3 text-sm text-slate-800">{{ $purchase->course->title }}</td>
                                <td class="px-4 py-3 text-sm text-slate-800">${{ number_format($purchase->amount, 2) }}</td>
                                <td class="px-4 py-3">
                                    <span class="inline-flex px-2.5 py-0.5 rounded-full text-xs font-medium
                                        {{ $purchase->status === 'approved' ? 'bg-emerald-100 text-emerald-800' : ($purchase->status === 'pending' ? 'bg-amber-100 text-amber-800' : 'bg-red-100 text-red-800') }}">
                                        {{ ucfirst($purchase->status) }}
                                    </span>
                                </td>
                                <td class="px-4 py-3 text-sm text-slate-600">{{ $purchase->created_at->format('d/m/Y H:i') }}</td>
                                <td class="px-4 py-3">
                                    @if($purchase->status === 'pending')
                                    <div class="flex gap-2">
                                        <form action="{{ route('admin.purchases.approve', $purchase) }}" method="POST" class="inline">
                                            @csrf
                                            <button type="submit" class="inline-flex items-center px-3 py-1.5 rounded-lg text-xs font-medium bg-emerald-600 text-white hover:bg-emerald-700">Aprobar</button>
                                        </form>
                                        <form action="{{ route('admin.purchases.reject', $purchase) }}" method="POST" class="inline">
                                            @csrf
                                            <button type="submit" class="inline-flex items-center px-3 py-1.5 rounded-lg text-xs font-medium bg-red-600 text-white hover:bg-red-700">Rechazar</button>
                                        </form>
                                    </div>
                                    @else
                                    <span class="text-slate-400 text-xs">—</span>
                                    @endif
                                </td>
                            </tr>
                            @empty
                            <tr>
                                <td colspan="7" class="px-4 py-12 text-center text-slate-500">No hay compras registradas</td>
                            </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
                @if($purchases->hasPages())
                <div class="px-4 py-3 border-t border-slate-200">{{ $purchases->links() }}</div>
                @endif
            </div>
        </div>
    </div>
</x-app-layout>
