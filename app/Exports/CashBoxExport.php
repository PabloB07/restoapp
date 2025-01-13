<?php

namespace App\Exports;

use App\Models\CashBox;
use Maatwebsite\Excel\Concerns\FromCollection;

class CashBoxExport implements FromCollection
{
    public function collection()
    {
        return CashBox::all();
    }
}
