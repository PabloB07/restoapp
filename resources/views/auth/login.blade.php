@extends('layouts.app')

@section('content')
<div class="min-h-screen bg-gray-50 font-sans">
    <div class="grid lg:grid-cols-2 min-h-screen">
        <div class="relative hidden lg:block">
            <img src="{{ asset('images/vastago_login.png') }}"
                 class="absolute inset-0 w-full h-full object-cover"
                 alt="Chef Vastago" />
            <div class="absolute inset-0 bg-gradient-to-t from-black/50 to-transparent"></div>
        </div>
        <div class="flex items-center justify-center p-8 lg:p-12">
            <form method="POST" action="{{ route('login') }}"
                  class="w-full max-w-md space-y-8 bg-white p-8 rounded-xl shadow-lg">
                @csrf

                <div class="space-y-2">
                    <h3 class="text-3xl font-bold text-gray-900">¡Bienvenido!</h3>
                    <p class="text-gray-600">Iniciar sesión en Vastago</p>
                </div>

                <div class="space-y-6">
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1">
                            Usuario
                        </label>
                        <input name="username" type="text" required
                               class="w-full px-4 py-3 rounded-lg border border-gray-300 focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition-colors"
                               placeholder="Ingresa tu usuario" />
                    </div>

                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1">
                            Contraseña
                        </label>
                        <input name="password" type="password" required
                               class="w-full px-4 py-3 rounded-lg border border-gray-300 focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition-colors"
                               placeholder="Ingresa tu contraseña" />
                    </div>
                </div>

                <button type="submit"
                        class="w-full py-3 px-4 text-sm font-semibold text-white bg-blue-600 rounded-lg hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:ring-offset-2 transition-colors">
                    Iniciar sesión
                </button>
            </form>
        </div>
    </div>
</div>
@endsection
