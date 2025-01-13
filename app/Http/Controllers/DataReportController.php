<?php

namespace App\Http\Controllers;

use App\Models\Order;
use Illuminate\Http\Request;
use Maatwebsite\Excel\Facades\Excel;
use App\Exports\DailyDataExport;

class DataReportController extends Controller
{
    public function index()
    {
        // Aquí puedes pasar datos adicionales a la vista si es necesario
        return view('data.report');
    }

    public function export()
    {
        return Excel::download(new DailyDataExport, 'reporte_diario.xlsx');
    }
}
