@props(['status'])

@if ($status)
    <div {{ $attributes->merge(['class' => 'font-medium text-sm text-emerald-600 rounded-lg bg-emerald-50 px-4 py-3']) }}>
        {{ $status }}
    </div>
@endif
