@props(['active'])

@php
$classes = ($active ?? false)
    ? 'block w-full ps-4 pe-4 py-2 text-left text-base font-medium rounded-lg bg-indigo-50 text-indigo-700 border-l-4 border-indigo-600'
    : 'block w-full ps-4 pe-4 py-2 text-left text-base font-medium rounded-lg text-slate-600 hover:bg-slate-100 hover:text-slate-900 border-l-4 border-transparent transition-colors';
@endphp

<a {{ $attributes->merge(['class' => $classes]) }}>
    {{ $slot }}
</a>
