@extends('layouts.app')

@section('content')
<div class="py-6 sm:py-12">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="bg-white overflow-hidden shadow-lg rounded-lg p-4 sm:p-6 mb-4 sm:mb-6">
            <div class="flex flex-col sm:flex-row justify-between items-start sm:items-center space-y-4 sm:space-y-0">
                <h2 class="text-xl sm:text-2xl font-semibold text-gray-800">Gestión de Mesas</h2>
                <a href="{{ route(auth()->user()->role . '.tables.create') }}" class="w-full sm:w-auto inline-flex items-center justify-center px-4 py-2 bg-blue-500 hover:bg-blue-600 text-white font-medium rounded-lg transition-colors duration-200">
                    <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/></svg>
                    Nuevo Mesa
                </a>
            </div>
        </div>

        <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-4 md:gap-6">
            @foreach($tables as $table)
<div class="relative bg-white overflow-hidden shadow-lg rounded-lg hover:shadow-xl transition-all duration-200">
    <svg width="16" height="100%" xmlns="http://www.w3.org/2000/svg" class="absolute left-0 top-0">
        <path d="M 8 0
                Q 4 4.8, 8 9.6
                T 8 19.2
                Q 4 24, 8 28.8
                T 8 38.4
                Q 4 43.2, 8 48
                T 8 57.6
                Q 4 62.4, 8 67.2
                T 8 76.8
                Q 4 81.6, 8 86.4
                T 8 96
                L 0 96
                L 0 0
                Z"
            fill="{{ $table->status === 'available' ? '#dcfce7' : '#fee2e2' }}"
            stroke="{{ $table->status === 'available' ? '#bbf7d0' : '#fecaca' }}"
            stroke-width="2"
            stroke-linecap="round"
            class="h-full"></path>
    </svg>
    <!-- Resto del código igual -->

                <div class="p-4 pl-8">
                    <div class="flex flex-col space-y-4">
                        <div class="flex flex-col sm:flex-row justify-between items-start sm:items-center space-y-2 sm:space-y-0">
                            <div>
                                <h3 class="text-lg font-semibold text-gray-900">Mesa {{ $table->number }}</h3>
                                <p class="text-sm text-gray-600">{{ $table->title }}</p>
                            </div>
                            <span class="inline-flex px-3 py-1 text-sm rounded-full {{ $table->status === 'available' ? 'bg-green-100 text-green-800' : 'bg-red-100 text-red-800' }}">
                                {{ $table->status === 'available' ? 'Disponible' : 'Ocupada' }}
                            </span>
                        </div>

                        <div class="space-y-2">
                            <p class="text-gray-600 flex items-center text-sm sm:text-base">
                                <svg class="w-5 h-5 mr-2 text-gray-400 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z"/>
                                </svg>
                                Capacidad: {{ $table->capacity }} personas
                            </p>
                        </div>

                        <div class="flex flex-col sm:flex-row justify-between items-start sm:items-center space-y-3 sm:space-y-0 pt-4 border-t border-gray-200">
                            <div class="flex space-x-2">
                                <a href="{{ route(auth()->user()->role . '.tables.show', $table) }}" class="inline-flex items-center text-sm text-blue-600 hover:text-blue-800 transition-colors duration-200">
                                    <svg class="w-4 h-4 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"/>
                                    </svg>
                                    Ver
                                </a>
                                <a href="{{ route(auth()->user()->role . '.tables.edit', $table) }}" class="inline-flex items-center text-sm text-yellow-600 hover:text-yellow-800 transition-colors duration-200">
                                    <svg class="w-4 h-4 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"/>
                                    </svg>
                                    Editar
                                </a>
                            </div>

                            <div class="flex items-center space-x-2 w-full sm:w-auto">
                                @if($table->status === 'available')
                                    <form action="{{ route(auth()->user()->role . '.tables.occupy', $table) }}" method="POST" class="inline">
                                        @csrf
                                        <button type="submit" class="w-full sm:w-auto inline-flex items-center text-sm text-green-600 hover:text-green-800 transition-colors duration-200">
                                            <svg class="w-4 h-4 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/>
                                            </svg>
                                            Crear Orden
                                        </button>
                                    </form>
                                @endif

                                <form action="{{ route(auth()->user()->role . '.tables.destroy', $table) }}" method="POST" class="inline">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" onclick="return confirm('¿Estás seguro de eliminar esta mesa?')" class="w-full sm:w-auto inline-flex items-center text-sm text-red-600 hover:text-red-800 transition-colors duration-200">
                                        <svg class="w-4 h-4 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"/>
                                        </svg>
                                        Eliminar
                                    </button>
                                </form>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            @endforeach
        </div>

        <div class="mt-6">
            {{ $tables->links() }}
        </div>
    </div>
</div>
@endsection
