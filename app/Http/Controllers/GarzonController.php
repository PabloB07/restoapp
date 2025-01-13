<?php

namespace App\Http\Controllers;

use App\Models\Order;
use App\Models\Table;
use Illuminate\Support\Facades\DB;
use Illuminate\Http\Request;

class GarzonController extends Controller
{
    public function index()
    {
        $user = auth()->user();
        $activeOrders = Order::where('status', 'pending')->with('table')->get();
        $tables = Table::all();
        return view('dashboard.dashboard_garzon', compact('activeOrders', 'tables'));
    }
}
