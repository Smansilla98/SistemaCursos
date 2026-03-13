@php
    $menuSections = [
        [
            'title' => 'Operaciones',
            'items' => [
                [
                    'label' => 'Dashboard',
                    'route' => 'admin.dashboard',
                    'icon'  => 'grid',
                    'active' => request()->routeIs('admin.dashboard'),
                ],
                [
                    'label' => 'Cursos',
                    'route' => 'admin.courses.index',
                    'icon'  => 'book-open',
                    'active' => request()->routeIs('admin.courses.*'),
                ],
                [
                    'label' => 'Usuarios',
                    'route' => 'admin.users.index',
                    'icon'  => 'users',
                    'active' => request()->routeIs('admin.users.*'),
                ],
                [
                    'label' => 'Compras',
                    'route' => 'admin.purchases.index',
                    'icon'  => 'credit-card',
                    'active' => request()->routeIs('admin.purchases.*'),
                ],
            ],
        ],
    ];
@endphp

<aside class="hidden lg:flex lg:flex-col lg:w-64 bg-slate-900 text-slate-100 min-h-screen shadow-nova-md">
    <div class="h-16 flex items-center px-5 border-b border-slate-800">
        <div class="flex items-center gap-2">
            <x-application-logo class="h-8 w-auto text-emerald-400" />
            <div class="flex flex-col leading-tight">
                <span class="text-sm font-semibold text-slate-100">Panel Admin</span>
                <span class="text-xs text-slate-400">{{ config('app.name', 'Cursos') }}</span>
            </div>
        </div>
    </div>

    <nav class="flex-1 overflow-y-auto py-4 space-y-4">
        @foreach($menuSections as $section)
            <div>
                <p class="px-5 text-xs font-semibold text-slate-500 uppercase tracking-wider mb-2">
                    {{ $section['title'] }}
                </p>
                <div class="space-y-1">
                    @foreach($section['items'] as $item)
                        @php
                            $isActive = $item['active'];
                        @endphp
                        <a
                            href="{{ route($item['route']) }}"
                            class="group flex items-center gap-3 px-5 py-2 text-sm font-medium rounded-lg transition-colors
                                   {{ $isActive ? 'bg-emerald-500/15 text-emerald-200' : 'text-slate-200 hover:bg-slate-800 hover:text-white' }}"
                        >
                            @switch($item['icon'])
                                @case('grid')
                                    <svg class="h-4 w-4 {{ $isActive ? 'text-emerald-300' : 'text-slate-400 group-hover:text-slate-100' }}" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <rect x="3" y="3" width="7" height="7" rx="1.5" />
                                        <rect x="14" y="3" width="7" height="7" rx="1.5" />
                                        <rect x="3" y="14" width="7" height="7" rx="1.5" />
                                        <rect x="14" y="14" width="7" height="7" rx="1.5" />
                                    </svg>
                                    @break
                                @case('book-open')
                                    <svg class="h-4 w-4 {{ $isActive ? 'text-emerald-300' : 'text-slate-400 group-hover:text-slate-100' }}" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6l-2-1-6 2v11l6-2 2 1m0-11l2-1 6 2v11l-6-2-2 1m0-11v11" />
                                    </svg>
                                    @break
                                @case('users')
                                    <svg class="h-4 w-4 {{ $isActive ? 'text-emerald-300' : 'text-slate-400 group-hover:text-slate-100' }}" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20v-2a3 3 0 00-3-3H6a3 3 0 00-3 3v2" />
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 11a4 4 0 100-8 4 4 0 000 8z" />
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 11a3 3 0 100-6 3 3 0 000 6z" />
                                    </svg>
                                    @break
                                @case('credit-card')
                                    <svg class="h-4 w-4 {{ $isActive ? 'text-emerald-300' : 'text-slate-400 group-hover:text-slate-100' }}" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <rect x="3" y="4" width="18" height="16" rx="2" />
                                        <path stroke-linecap="round" stroke-width="2" d="M3 10h18" />
                                        <path stroke-linecap="round" stroke-width="2" d="M7 16h4" />
                                    </svg>
                                    @break
                            @endswitch
                            <span>{{ $item['label'] }}</span>
                        </a>
                    @endforeach
                </div>
            </div>
        @endforeach
    </nav>

    <div class="border-t border-slate-800 px-5 py-3 text-xs text-slate-500">
        <div class="flex items-center justify-between">
            <span>{{ auth()->user()->name ?? '' }}</span>
            <span class="uppercase tracking-wide text-[10px] bg-slate-800/70 px-2 py-0.5 rounded-full text-slate-300">
                ADMIN
            </span>
        </div>
    </div>
</aside>

