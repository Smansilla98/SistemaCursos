<x-guest-layout>
    <h2 class="text-xl font-semibold text-slate-800 mb-2">Verificá tu email</h2>
    <p class="text-sm text-slate-600 mb-6">Antes de continuar, hace clic en el enlace que te enviamos por email. Si no lo recibiste, podemos reenviártelo.</p>

    @if (session('status') == 'verification-link-sent')
    <div class="mb-4 font-medium text-sm text-emerald-600 rounded-lg bg-emerald-50 px-4 py-3">
        Se envió un nuevo enlace de verificación a tu email.
    </div>
    @endif

    <div class="space-y-4">
        <form method="POST" action="{{ route('verification.send') }}">
            @csrf
            <x-primary-button class="w-full">Reenviar email de verificación</x-primary-button>
        </form>

        <form method="POST" action="{{ route('logout') }}">
            @csrf
            <button type="submit" class="w-full text-sm font-medium text-slate-600 hover:text-indigo-600">
                Cerrar sesión
            </button>
        </form>
    </div>
</x-guest-layout>
