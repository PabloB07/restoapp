<?php

namespace App\Http\Controllers;

use App\Models\CompanySettings;
use Illuminate\Http\Request;

class CompanySettingsController extends Controller
{
    public function index()
    {
        $settings = CompanySettings::first();
        return view('admin.company-settings.index', compact('settings'));
    }

    public function update(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'business_line' => 'required|string|max:255',
            'address' => 'required|string|max:255',
            'rut' => 'required|string|max:20',
            'email' => 'required|email|max:255',
            'phone' => 'required|string|max:20',
        ]);

        $settings = CompanySettings::first();
        $settings->update($request->all());

        return redirect()->back()->with('success', 'Configuración actualizada');
    }
}
