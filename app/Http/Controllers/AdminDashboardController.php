<?php

namespace App\Http\Controllers;

use App\Models\Order;
use App\Models\Table;
use Carbon\Carbon;

class AdminDashboardController extends Controller
{
   public function index()
   {
       $today = Carbon::today();

       // Datos principales
       $usedTables = Table::where('status', 'occupied')->count();
       $freeTables = Table::count() - $usedTables;
       $totalOrders = Order::count();
       $totalIncome = Order::sum('total');

       // Datos del día
       $tablesUsedToday = Table::whereHas('orders', function($query) use ($today) {
           $query->whereDate('created_at', $today);
       })->count();

       $dailyIncome = Order::whereDate('created_at', $today)
           ->where('status', 'completed')
           ->sum('total') ?? 0;

       $dailyOrders = Order::whereDate('created_at', $today)->count();

       return view('dashboard.index', compact(
           'totalOrders',
           'totalIncome',
           'dailyIncome',
           'dailyOrders',
           'usedTables',
           'freeTables',
           'tablesUsedToday'
       ));
   }
}
