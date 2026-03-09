<x-app-layout>
    <x-slot name="header">
        <div class="flex flex-col sm:flex-row sm:justify-between sm:items-start gap-4">
            <div>
                <h1 class="text-2xl font-bold text-slate-800 tracking-tight">{{ $user->name }}</h1>
                <p class="mt-1 text-sm text-slate-500">{{ $user->email }}</p>
            </div>
            <div class="flex gap-2 shrink-0">
                <a href="{{ route('admin.users.edit', $user) }}" class="btn-nova">Editar</a>
                <a href="{{ route('admin.users.index') }}" class="inline-flex items-center justify-center rounded-lg px-4 py-2 text-sm font-medium text-slate-700 bg-white border border-slate-300 shadow-sm hover:bg-slate-50">Volver</a>
            </div>
        </div>
    </x-slot>

    <div class="py-8">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8 space-y-6">
            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                <div class="card-nova p-6">
                    <h3 class="text-lg font-semibold text-slate-800 mb-4">Información personal</h3>
                    <div class="space-y-2 text-sm">
                        <p><span class="font-medium text-slate-700">Nombre:</span> {{ $user->name }}</p>
                        <p><span class="font-medium text-slate-700">Email:</span> {{ $user->email }}</p>
                        <p>
                            <span class="font-medium text-slate-700">Rol:</span>
                            <span class="inline-flex px-2.5 py-0.5 rounded-full text-xs font-medium {{ $user->hasRole('admin') ? 'bg-indigo-100 text-indigo-800' : 'bg-slate-100 text-slate-700' }}">{{ $user->roles->first()->name ?? 'Sin rol' }}</span>
                        </p>
                        <p><span class="font-medium text-slate-700">Registrado:</span> {{ $user->created_at->format('d/m/Y') }}</p>
                    </div>
                </div>
                <div class="card-nova p-6">
                    <h3 class="text-lg font-semibold text-slate-800 mb-4">Estadísticas</h3>
                    <div class="space-y-2 text-sm">
                        <p><span class="font-medium text-slate-700">Cursos comprados:</span> {{ $user->courses()->count() }}</p>
                        <p><span class="font-medium text-slate-700">Compras totales:</span> {{ $user->purchases()->count() }}</p>
                        <p><span class="font-medium text-slate-700">Compras aprobadas:</span> {{ $user->purchases()->where('status', 'approved')->count() }}</p>
                    </div>
                </div>
            </div>

            <div class="card-nova overflow-hidden">
                <div class="p-6 border-b border-slate-200">
                    <h3 class="text-lg font-semibold text-slate-800">Compras</h3>
                </div>
                <div class="overflow-x-auto">
                    <table class="min-w-full divide-y divide-slate-200">
                        <thead class="bg-slate-50">
                            <tr>
                                <th class="px-4 py-3 text-left text-xs font-medium text-slate-500 uppercase tracking-wider">Curso</th>
                                <th class="px-4 py-3 text-left text-xs font-medium text-slate-500 uppercase tracking-wider">Monto</th>
                                <th class="px-4 py-3 text-left text-xs font-medium text-slate-500 uppercase tracking-wider">Estado</th>
                                <th class="px-4 py-3 text-left text-xs font-medium text-slate-500 uppercase tracking-wider">Fecha</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-slate-200">
                            @forelse($user->purchases as $purchase)
                            <tr class="hover:bg-slate-50">
                                <td class="px-4 py-3 text-sm text-slate-800">{{ $purchase->course->title }}</td>
                                <td class="px-4 py-3 text-sm text-slate-800">${{ number_format($purchase->amount, 2) }}</td>
                                <td class="px-4 py-3">
                                    <span class="inline-flex px-2.5 py-0.5 rounded-full text-xs font-medium {{ $purchase->status === 'approved' ? 'bg-emerald-100 text-emerald-800' : ($purchase->status === 'pending' ? 'bg-amber-100 text-amber-800' : 'bg-red-100 text-red-800') }}">{{ ucfirst($purchase->status) }}</span>
                                </td>
                                <td class="px-4 py-3 text-sm text-slate-600">{{ $purchase->created_at->format('d/m/Y') }}</td>
                            </tr>
                            @empty
                            <tr>
                                <td colspan="4" class="px-4 py-8 text-center text-slate-500">No hay compras</td>
                            </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
