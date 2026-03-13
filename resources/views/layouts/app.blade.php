<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <meta name="csrf-token" content="{{ csrf_token() }}">

        <title>{{ config('app.name', 'Laravel') }}</title>

        <!-- Fonts - Inter (Nova-style) -->
        <link rel="preconnect" href="https://fonts.bunny.net">
        <link href="https://fonts.bunny.net/css?family=inter:400,500,600,700&display=swap" rel="stylesheet" />

        <!-- Assets: Vite (build) o fallback CDN si no hay manifest en producción -->
        @if(file_exists(public_path('build/manifest.json')) || file_exists(public_path('hot')))
            @vite(['resources/css/app.css', 'resources/js/app.js'])
        @else
            <script src="https://cdn.tailwindcss.com"></script>
            <script>
                tailwind.config = {
                    theme: { extend: { fontFamily: { sans: ['Inter', 'sans-serif'] } } }
                }
            </script>
            <link rel="stylesheet" href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700&display=swap">
        @endif
    </head>
    <body class="font-sans antialiased bg-slate-50">
        @php
            $showAdminSidebar = auth()->check()
                && auth()->user()->hasRole('admin')
                && request()->routeIs('admin.*');
        @endphp

        <div class="min-h-screen bg-slate-50">
            @include('layouts.navigation')

            <div class="{{ $showAdminSidebar ? 'lg:flex' : '' }}">
                @if($showAdminSidebar)
                    @include('layouts.admin-sidebar')
                @endif

                <div class="flex-1">
                    <!-- Page Heading -->
                    @isset($header)
                        <header class="bg-white border-b border-slate-200 shadow-nova">
                            <div class="max-w-7xl mx-auto py-6 px-4 sm:px-6 lg:px-8">
                                {{ $header }}
                            </div>
                        </header>
                    @endisset

                    <!-- Page Content -->
                    <main class="py-8">
                        {{ $slot }}
                    </main>
                </div>
            </div>
        </div>
    </body>
</html>
