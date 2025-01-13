<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;

class CheckRole
{
    public function handle(Request $request, Closure $next, ...$roles)
    {
        if (!auth()->check()) {
            return redirect('login');
        }

        // Si el usuario es admin, permitir acceso a todo
        if (auth()->user()->role === 'admin') {
            return $next($request);
        }

        // Para otros roles, verificar si tienen el permiso específico
        if (!in_array(auth()->user()->role, $roles)) {
            return redirect('/')->with('error', 'No tienes permiso para acceder a esta sección.');
        }

        return $next($request);
    }
}
