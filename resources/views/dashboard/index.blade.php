@extends('layouts.app')

@section('content')
    <div class="min-h-screen bg-gray-50">
        <div class="p-6 mx-auto max-w-7xl">
            <!-- Header -->
            <header class="flex flex-col lg:flex-row lg:items-center justify-between gap-6 mb-8">
                <div class="space-y-4">
                    <h1 class="text-2xl lg:text-3xl font-bold text-gray-900">{{ $companySettings->name }} - Panel de Control
                    </h1>
                    <nav class="grid grid-cols-3 lg:flex gap-3">
                        @foreach (['day' => 'Hoy', 'week' => 'Esta Semana', 'month' => 'Este Mes'] as $key => $label)
                            <button type="button" onclick="updateTimeRange('{{ $key }}')"
                                @class([
                                    'px-4 py-2.5 rounded-lg text-sm font-semibold transition-all duration-200',
                                    'bg-blue-600 text-white shadow-sm hover:bg-blue-700' => $timeRange === $key,
                                    'bg-white text-gray-700 shadow-sm ring-1 ring-gray-200 hover:bg-gray-50' =>
                                        $timeRange !== $key,
                                ])>
                                {{ $label }}
                            </button>
                        @endforeach
                    </nav>
                </div>
                <div class="bg-white p-4 rounded-lg shadow-sm">
                    <a href="{{ route('company.settings.show') }}" class="w-full py-2 px-4 bg-blue-600 text-white rounded-md hover:bg-blue-700 focus:outline-none">Configurar Empresa</a>
                </div>
            </header>

            <!-- Stats Grid -->
            <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-6 mb-8">
                <!-- Sales Card -->
                <div
                    class="relative group overflow-hidden bg-gradient-to-br from-green-500 to-green-600 rounded-xl p-6 shadow-lg transition-transform hover:scale-[1.02]">
                    <div class="relative z-10">
                        <h3 class="text-sm text-green-100">Ventas Totales</h3>
                        <p class="text-3xl font-bold text-white mt-2">
                            ${{ number_format($totalIncome, 0, ',', '.') }}
                        </p>
                        @if ($previousPeriodIncome > 0)
                            <p class="text-sm mt-3">
                                @php
                                    $percentChange =
                                        (($totalIncome - $previousPeriodIncome) / $previousPeriodIncome) * 100;
                                    $isPositive = $percentChange > 0;
                                @endphp
                                <span class="flex items-center gap-1 {{ $isPositive ? 'text-green-100' : 'text-red-100' }}">
                                    {!! $isPositive ? '↑' : '↓' !!}
                                    {{ number_format(abs($percentChange), 1) }}%
                                </span>
                            </p>
                        @endif
                    </div>
                </div>

                <!-- Daily Average Card -->
                <div
                    class="relative group overflow-hidden bg-gradient-to-br from-blue-500 to-blue-600 rounded-xl p-6 shadow-lg transition-transform hover:scale-[1.02]">
                    <div class="relative z-10">
                        <h3 class="text-sm text-blue-100">Promedio Diario</h3>
                        <p class="text-3xl font-bold text-white mt-2">
                            ${{ number_format($averageDailyIncome, 0, ',', '.') }}
                        </p>
                        <p class="text-sm text-blue-100 mt-3">Por día</p>
                    </div>
                </div>

                <!-- Orders Card -->
                <div
                    class="relative group overflow-hidden bg-gradient-to-br from-purple-500 to-purple-600 rounded-xl p-6 shadow-lg transition-transform hover:scale-[1.02]">
                    <div class="relative z-10">
                        <h3 class="text-sm text-purple-100">Total Órdenes</h3>
                        <p class="text-3xl font-bold text-white mt-2">{{ $totalOrders }}</p>
                        <p class="text-sm text-purple-100 mt-3">
                            Promedio: ${{ number_format($averageOrderValue, 0, ',', '.') }}/orden
                        </p>
                    </div>
                </div>

                <!-- Tables Card -->
                <div
                    class="relative group overflow-hidden bg-gradient-to-br from-orange-500 to-orange-600 rounded-xl p-6 shadow-lg transition-transform hover:scale-[1.02]">
                    <div class="relative z-10">
                        <h3 class="text-sm text-orange-100">Mesas Activas</h3>
                        <p class="text-3xl font-bold text-white mt-2">
                            {{ $usedTables }} / {{ $totalTables }}
                        </p>
                        <p class="text-sm text-orange-100 mt-3">
                            {{ number_format($tableOccupancyRate, 1) }}% ocupación
                        </p>
                    </div>
                </div>
            </div>

            <!-- Charts -->
            <div class="grid grid-cols-1 lg:grid-cols-2 gap-6 mb-8">
                <div class="bg-white rounded-xl shadow-lg p-6">
                    <h3 class="text-lg font-semibold text-gray-900 mb-6">Ventas por Período</h3>
                    <div class="h-80">
                        <canvas id="salesChart"></canvas>
                    </div>
                </div>

                <div class="bg-white rounded-xl shadow-lg p-6">
                    <h3 class="text-lg font-semibold text-gray-900 mb-6">Productos Más Vendidos</h3>
                    <div class="h-80">
                        <canvas id="productsChart"></canvas>
                    </div>
                </div>
            </div>

            <!-- Table -->
            <div class="bg-white rounded-xl shadow-lg overflow-hidden">
                <div class="p-6 border-b border-gray-200">
                    <h3 class="text-lg font-semibold text-gray-900">Resumen Detallado</h3>
                </div>
                <div class="overflow-x-auto">
                    <table class="w-full">
                        <thead class="bg-gray-50">
                            <tr>
                                <th class="px-6 py-4 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                    Fecha
                                </th>
                                <th class="px-6 py-4 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                    Ventas
                                </th>
                                <th class="px-6 py-4 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                    Órdenes
                                </th>
                                <th
                                    class="hidden lg:table-cell px-6 py-4 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                    Promedio
                                </th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-gray-200 bg-white">
                            @foreach ($dailySummary as $day)
                                <tr class="hover:bg-gray-50 transition-colors">
                                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">
                                        {{ Carbon\Carbon::parse($day->date)->format('d/m/Y') }}
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">
                                        ${{ number_format($day->total_sales, 0, ',', '.') }}
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">
                                        {{ $day->total_orders }}
                                    </td>
                                    <td class="hidden lg:table-cell px-6 py-4 whitespace-nowrap text-sm text-gray-900">
                                        ${{ number_format($day->average_order, 0, ',', '.') }}
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>

    @push('scripts')
        <script>
            document.addEventListener('DOMContentLoaded', () => {
                const updateTimeRange = range => {
                    window.location.href = `{{ route('admin.panel') }}?timeRange=${range}`;
                };
                window.updateTimeRange = updateTimeRange;

                const chartConfig = {
                    responsive: true,
                    maintainAspectRatio: false,
                    plugins: {
                        legend: {
                            display: false
                        },
                        tooltip: {
                            backgroundColor: 'rgba(0, 0, 0, 0.8)',
                            padding: 12,
                            titleColor: '#fff',
                            titleFont: {
                                size: 14,
                                weight: 'bold'
                            },
                            bodyColor: '#fff',
                            bodyFont: {
                                size: 13
                            },
                            displayColors: false,
                            callbacks: {
                                label: (context) => {
                                    return `${new Intl.NumberFormat('es-CL').format(context.raw)}`;
                                }
                            }
                        }
                    },
                    scales: {
                        y: {
                            beginAtZero: true,
                            ticks: {
                                callback: (value) => `$${new Intl.NumberFormat('es-CL').format(value)}`
                            }
                        }
                    }
                };

                new Chart(document.getElementById('salesChart').getContext('2d'), {
                    type: 'line',
                    data: {
                        labels: @json($salesData->pluck('date')),
                        datasets: [{
                            data: @json($salesData->pluck('total')),
                            borderColor: '#4F46E5',
                            backgroundColor: 'rgba(79, 70, 229, 0.1)',
                            borderWidth: 2,
                            tension: 0.3,
                            fill: true
                        }]
                    },
                    options: chartConfig
                });

                new Chart(document.getElementById('productsChart').getContext('2d'), {
                    type: 'bar',
                    data: {
                        labels: @json($topProducts->pluck('name')),
                        datasets: [{
                            data: @json($topProducts->pluck('quantity')),
                            backgroundColor: [
                                'rgba(79, 70, 229, 0.8)',
                                'rgba(124, 58, 237, 0.8)',
                                'rgba(236, 72, 153, 0.8)',
                                'rgba(245, 158, 11, 0.8)',
                                'rgba(16, 185, 129, 0.8)'
                            ],
                            borderRadius: 6
                        }]
                    },
                    options: {
                        ...chartConfig,
                        scales: {
                            ...chartConfig.scales,
                            y: {
                                beginAtZero: true,
                                ticks: {
                                    stepSize: 1,
                                    callback: (value) => Math.floor(value)
                                }
                            }
                        }
                    }
                });
            });
        </script>
    @endpush
@endsection
