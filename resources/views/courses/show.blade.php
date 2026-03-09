<x-app-layout>
    <x-slot name="header">
        <h1 class="text-2xl font-bold text-slate-800 tracking-tight">{{ $course->title }}</h1>
        <p class="mt-1 text-sm text-slate-500">Detalle del curso</p>
    </x-slot>

    <div class="py-8">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="card-nova overflow-hidden">
                <div class="p-6 md:p-8">
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-8">
                        <div>
                            @if($course->thumbnail)
                            <img src="{{ asset('storage/' . $course->thumbnail) }}" alt="{{ $course->title }}" class="w-full h-64 object-cover rounded-xl">
                            @else
                            <div class="w-full h-64 bg-gradient-to-br from-indigo-500 to-indigo-700 rounded-xl flex items-center justify-center">
                                <span class="text-white text-4xl font-bold">{{ substr($course->title, 0, 2) }}</span>
                            </div>
                            @endif
                        </div>
                        <div>
                            <h2 class="text-2xl font-bold text-slate-800 mb-4">{{ $course->title }}</h2>
                            <p class="text-slate-600 whitespace-pre-line mb-6">{{ $course->description }}</p>

                            <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4 border-t border-slate-200 pt-6">
                                <span class="text-3xl font-bold text-indigo-600">${{ number_format($course->price, 2) }}</span>

                                @auth
                                    @if(auth()->user()->hasAccessToCourse($course->id))
                                        <a href="{{ route('student.courses.show', $course) }}" class="btn-nova text-center">
                                            Acceder al curso
                                        </a>
                                    @else
                                        <form action="{{ route('student.courses.purchase', $course) }}" method="POST" class="inline">
                                            @csrf
                                            <button type="submit" class="btn-nova w-full sm:w-auto">Comprar curso</button>
                                        </form>
                                    @endif
                                @else
                                    <a href="{{ route('login') }}" class="btn-nova text-center">Iniciar sesión para comprar</a>
                                @endauth
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
