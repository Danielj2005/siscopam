<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class EnsureSuperAdmin
{
    /**
     * Handle an incoming request.
     */
    public function handle(Request $request, Closure $next): Response
    {
        // Verifica si está autenticado y si es superusuario 
        $user = auth()->user();

        if (! $user || ! $user->role || $user->role->nombre !== 'SUPER_ADMIN') {
            abort(403, 'Acceso denegado. Esta sección es exclusiva para el Super Administrador.');
        }

        return $next($request); 
    }
}