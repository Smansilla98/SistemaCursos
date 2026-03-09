@props(['disabled' => false])

<input @disabled($disabled) {{ $attributes->merge(['class' => 'input-nova border-slate-300 text-slate-800 placeholder-slate-400 block w-full rounded-lg shadow-sm focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500 disabled:opacity-50 disabled:cursor-not-allowed']) }}>
