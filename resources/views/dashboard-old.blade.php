@extends('layouts.app')

@section('content')
<div class="py-12">
    <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
        <h2 class="text-2xl font-semibold mb-6">Reporte General Total</h2>

        <!-- Agregar el enlace al Reporte de Datos -->
        <div class="mb-4">
            <a href="{{ route('data.report.export') }}" class="bg-blue-500 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded">
                <i class="fas fa-download mr-2"></i> Descargar Reporte
            </a>
        </div>

        <div class="grid grid-cols-1 md:grid-cols-3 gap-4 mb-6">
            <div class="bg-white p-4 rounded-lg shadow">
                <h3 class="text-lg font-semibold">Mesas Usadas</h3>
                <canvas id="usedTablesChart"></canvas>
            </div>
            <div class="bg-white p-4 rounded-lg shadow">
                <h3 class="text-lg font-semibold">Recaudación Total</h3>
                <canvas id="totalIncomeChart"></canvas>
            </div>
            <div class="bg-white p-4 rounded-lg shadow">
                <h3 class="text-lg font-semibold">Total de Órdenes</h3>
                <canvas id="totalOrdersChart"></canvas>
            </div>
        </div>

        <!-- Productos Más Vendidos -->
        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6 mb-6">
            <!-- Menú Más Pedido -->
            <div class="bg-white p-6 rounded-lg shadow">
                <h3 class="text-lg font-semibold">Menú Más Pedido</h3>
                <div class="flex items-center justify-between">
                    @if($topMenu)
                        <div>
                            <p class="text-xl font-bold">{{ $topMenu->product->name }}</p>
                            <p class="text-sm text-gray-600">{{ $topMenu->total_quantity }} unidades</p>
                        </div>
                        <span class="badge badge-success">Top</span>
                    @else
                        <p class="text-gray-600">Sin datos para hoy</p>
                    @endif
                </div>
            </div>

            <!-- Bebestible Más Pedido -->
            <div class="bg-white p-6 rounded-lg shadow">
                <h3 class="text-lg font-semibold">Bebestible Más Pedido</h3>
                <div class="flex items-center justify-between">
                    @if($topDrink)
                        <div>
                            <p class="text-xl font-bold">{{ $topDrink->product->name }}</p>
                            <p class="text-sm text-gray-600">{{ $topDrink->total_quantity }} unidades</p>
                        </div>
                        <span class="badge badge-success">Top</span>
                    @else
                        <p class="text-gray-600">Sin datos para hoy</p>
                    @endif
                </div>
            </div>
        </div>
    </div>
</div>

@push('scripts')
<script>
    const usedTablesCtx = document.getElementById('usedTablesChart').getContext('2d');
    const totalIncomeCtx = document.getElementById('totalIncomeChart').getContext('2d');
    const totalOrdersCtx = document.getElementById('totalOrdersChart').getContext('2d');

    const usedTablesChart = new Chart(usedTablesCtx, {
        type: 'doughnut',
        data: {
            labels: ['Mesas Usadas', 'Mesas Libres'],
            datasets: [{
                data: [{{ $usedTables }}, {{ $availableTables }}],
                backgroundColor: ['#FF6384', '#36A2EB']
            }]
        }
    });

    const totalIncomeChart = new Chart(totalIncomeCtx, {
        type: 'bar',
        data: {
            labels: ['Recaudación Total'],
            datasets: [{
                label: 'Total',
                data: [{{ $totalIncome }}],
                backgroundColor: '#4BC0C0',
            }]
        }
    });

    const totalOrdersChart = new Chart(totalOrdersCtx, {
        type: 'bar',
        data: {
            labels: ['Total de Órdenes'],
            datasets: [{
                label: 'Órdenes',
                data: [{{ $totalOrders }}],
                borderColor: '#FFCE56',
                fill: false,
            }]
        }
    });
</script>
@endpush
@endsection
