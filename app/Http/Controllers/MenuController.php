<?php

namespace App\Http\Controllers;

use App\Models\Menu;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class MenuController extends Controller
{
    public function index()
    {
        $menus = Menu::all();
        return view('menus.index', compact('menus'));
    }

    public function show(Menu $menu)
    {
        return response()->file(storage_path('app/public/' . $menu->file_path));
    }
    public function create()
    {
        return view('menus.create');
    }

    public function store(Request $request)
    {
        $request->validate([
            'menu_pdf' => 'required|mimes:pdf|max:2048',
        ]);

        if ($request->file('menu_pdf')) {
            $filePath = $request->file('menu_pdf')->store('menus', 'public');

            Menu::create([
                'file_path' => $filePath,
            ]);


            return redirect()->route(auth()->user()->role . '.menus.index')->with('success', 'Carta subida exitosamente.');
        }

        return back()->with('error', 'Error al subir la carta.');
    }
}
