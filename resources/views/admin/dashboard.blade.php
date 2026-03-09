<x-app-layout>
    <x-slot name="header">
        <h1 class="text-2xl font-bold text-slate-800 tracking-tight">Dashboard</h1>
        <p class="mt-1 text-sm text-slate-500">Panel de administración</p>
    </x-slot>

    <div class="py-8">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6 mb-8">
                <div class="card-nova p-6">
                    <div class="flex items-center gap-4">
                        <div class="flex-shrink-0 bg-indigo-100 rounded-xl p-3">
                            <svg class="h-6 w-6 text-indigo-600" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6.253v13m0-13C10.832 5.477 9.246 5 7.5 5S4.168 5.477 3 6.253v13C4.168 18.477 5.754 18 7.5 18s3.332.477 4.5 1.253m0-13C13.168 5.477 14.754 5 16.5 5c1.747 0 3.332.477 4.5 1.253v13C19.832 18.477 18.247 18 16.5 18c-1.746 0-3.332.477-4.5 1.253" />
                            </svg>
                        </div>
                        <div>
                            <p class="text-sm font-medium text-slate-500">Total cursos</p>
                            <p class="text-xl font-semibold text-slate-800">{{ $stats['total_courses'] }}</p>
                        </div>
                    </div>
                </div>

                <div class="card-nova p-6">
                    <div class="flex items-center gap-4">
                        <div class="flex-shrink-0 bg-emerald-100 rounded-xl p-3">
                            <svg class="h-6 w-6 text-emerald-600" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197M13 7a4 4 0 11-8 0 4 4 0 018 0z" />
                            </svg>
                        </div>
                        <div>
                            <p class="text-sm font-medium text-slate-500">Alumnos</p>
                            <p class="text-xl font-semibold text-slate-800">{{ $stats['total_students'] }}</p>
                        </div>
                    </div>
                </div>

                <div class="card-nova p-6">
                    <div class="flex items-center gap-4">
                        <div class="flex-shrink-0 bg-amber-100 rounded-xl p-3">
                            <svg class="h-6 w-6 text-amber-600" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z" />
                            </svg>
                        </div>
                        <div>
                            <p class="text-sm font-medium text-slate-500">Compras aprobadas</p>
                            <p class="text-xl font-semibold text-slate-800">{{ $stats['approved_purchases'] }}</p>
                        </div>
                    </div>
                </div>

                <div class="card-nova p-6">
                    <div class="flex items-center gap-4">
                        <div class="flex-shrink-0 bg-slate-100 rounded-xl p-3">
                            <svg class="h-6 w-6 text-slate-600" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z" />
                            </svg>
                        </div>
                        <div>
                            <p class="text-sm font-medium text-slate-500">Compras pendientes</p>
                            <p class="text-xl font-semibold text-slate-800">{{ $stats['pending_purchases'] }}</p>
                        </div>
                    </div>
                </div>
            </div>

            <div class="card-nova mb-8">
                <div class="p-6 border-b border-slate-200">
                    <h3 class="text-lg font-semibold text-slate-800">Compras recientes</h3>
                </div>
                <div class="overflow-x-auto">
                    <table class="min-w-full divide-y divide-slate-200">
                        <thead class="bg-slate-50">
                            <tr>
                                <th class="px-4 py-3 text-left text-xs font-medium text-slate-500 uppercase tracking-wider">Estudiante</th>
                                <th class="px-4 py-3 text-left text-xs font-medium text-slate-500 uppercase tracking-wider">Curso</th>
                                <th class="px-4 py-3 text-left text-xs font-medium text-slate-500 uppercase tracking-wider">Monto</th>
                                <th class="px-4 py-3 text-left text-xs font-medium text-slate-500 uppercase tracking-wider">Estado</th>
                                <th class="px-4 py-3 text-left text-xs font-medium text-slate-500 uppercase tracking-wider">Fecha</th>
                                <th class="px-4 py-3 text-left text-xs font-medium text-slate-500 uppercase tracking-wider">Acciones</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-slate-200">
                            @forelse($recentPurchases as $purchase)
                            <tr class="hover:bg-slate-50">
                                <td class="px-4 py-3 text-sm text-slate-800">{{ $purchase->user->name }}</td>
                                <td class="px-4 py-3 text-sm text-slate-800">{{ $purchase->course->title }}</td>
                                <td class="px-4 py-3 text-sm text-slate-800">${{ number_format($purchase->amount, 2) }}</td>
                                <td class="px-4 py-3">
                                    <span class="inline-flex px-2.5 py-0.5 rounded-full text-xs font-medium
                                        {{ $purchase->status === 'approved' ? 'bg-emerald-100 text-emerald-800' : ($purchase->status === 'pending' ? 'bg-amber-100 text-amber-800' : 'bg-red-100 text-red-800') }}">
                                        {{ ucfirst($purchase->status) }}
                                    </span>
                                </td>
                                <td class="px-4 py-3 text-sm text-slate-600">{{ $purchase->created_at->format('d/m/Y') }}</td>
                                <td class="px-4 py-3 text-sm">
                                    @if($purchase->status === 'pending')
                                    <div class="flex gap-3">
                                        <form action="{{ route('admin.purchases.approve', $purchase) }}" method="POST" class="inline">
                                            @csrf
                                            <button type="submit" class="font-medium text-emerald-600 hover:text-emerald-700">Aprobar</button>
                                        </form>
                                        <form action="{{ route('admin.purchases.reject', $purchase) }}" method="POST" class="inline">
                                            @csrf
                                            <button type="submit" class="font-medium text-red-600 hover:text-red-700">Rechazar</button>
                                        </form>
                                    </div>
                                    @endif
                                </td>
                            </tr>
                            @empty
                            <tr>
                                <td colspan="6" class="px-4 py-8 text-center text-slate-500">No hay compras recientes</td>
                            </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>

            <div class="card-nova">
                <div class="p-6 border-b border-slate-200">
                    <h3 class="text-lg font-semibold text-slate-800">Cursos recientes</h3>
                </div>
                <div class="p-6">
                    <div class="space-y-3">
                        @forelse($recentCourses as $course)
                        <div class="flex items-center justify-between p-4 rounded-xl bg-slate-50 hover:bg-slate-100 transition-colors">
                            <div>
                                <h4 class="font-semibold text-slate-800">{{ $course->title }}</h4>
                                <p class="text-sm text-slate-500">${{ number_format($course->price, 2) }}</p>
                            </div>
                            <a href="{{ route('admin.courses.show', $course) }}" class="text-sm font-medium text-indigo-600 hover:text-indigo-700">Ver</a>
                        </div>
                        @empty
                        <p class="text-slate-500">No hay cursos recientes</p>
                        @endforelse
                    </div>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
