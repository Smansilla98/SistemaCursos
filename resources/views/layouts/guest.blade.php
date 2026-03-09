<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <meta name="csrf-token" content="{{ csrf_token() }}">

        <title>{{ config('app.name', 'Laravel') }}</title>

        <link rel="preconnect" href="https://fonts.bunny.net">
        <link href="https://fonts.bunny.net/css?family=inter:400,500,600,700&display=swap" rel="stylesheet" />

        @if(file_exists(public_path('build/manifest.json')) || file_exists(public_path('hot')))
            @vite(['resources/css/app.css', 'resources/js/app.js'])
        @else
            <script src="https://cdn.tailwindcss.com"></script>
            <link rel="stylesheet" href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700&display=swap">
        @endif
    </head>
    <body class="font-sans text-slate-800 antialiased bg-slate-50">
        <div class="min-h-screen flex flex-col sm:justify-center items-center py-12 px-4">
            <a href="/" class="flex items-center gap-2 mb-8">
                <x-application-logo class="w-12 h-12 fill-current text-indigo-600" />
                <span class="text-xl font-semibold text-slate-800">{{ config('app.name', 'Cursos') }}</span>
            </a>

            <div class="w-full sm:max-w-md card-nova p-8">
                {{ $slot }}
            </div>

            <p class="mt-8 text-center text-sm text-slate-500">
                <a href="{{ url('/') }}" class="font-medium text-indigo-600 hover:text-indigo-500">Volver al inicio</a>
            </p>
        </div>
    </body>
</html>
