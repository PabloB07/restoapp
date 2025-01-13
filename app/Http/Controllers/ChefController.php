<?php

namespace App\Http\Controllers;

use App\Models\Order;
use App\Models\OrderItem;
use App\Models\CashBox;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;
use Illuminate\Http\Request;

class ChefController extends Controller
{
    public function index()
    {
        $today = Carbon::today();
        $totalOrders = Order::count();
        $totalIncome = Order::sum('total');

        // Productos más pedidos (filtrando por tipo)
        $topMenu = OrderItem::select('product_id', DB::raw('SUM(quantity) as total_quantity'))
            ->whereHas('product', function($query) {
                $query->where('type', 'comida');
            })
            ->groupBy('product_id')
            ->orderBy('total_quantity', 'desc')
            ->with('product')
            ->first();

        // Bebestible más pedido (filtrando por tipo)
        $topDrink = OrderItem::select('product_id', DB::raw('SUM(quantity) as total_quantity'))
            ->whereHas('product', function($query) {
                $query->where('type', 'bebestible');
            })
            ->groupBy('product_id')
            ->orderBy('total_quantity', 'desc')
            ->with('product')
            ->first();

        // Obtener ingresos diarios para el gráfico
        $dailyIncomeData = CashBox::select('date', 'total_income')
            ->orderBy('date', 'asc')
            ->take(7)
            ->get();

        return view('dashboard.dashboard_chef', compact(
            'totalOrders',
            'totalIncome',
            'topMenu',
            'topDrink',
            'dailyIncomeData'
        ));
    }
}
