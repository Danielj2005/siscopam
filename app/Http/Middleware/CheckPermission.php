<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class CheckPermission
{
    /**
     * Manejar la petición entrante y verificar si el rol del usuario posee el permiso solicitado.
     */
    public function handle(Request $request, Closure $next, string $permission): Response
    {
        $user = $request->user();

        if (! $user) {
            return response()->json([
                'message' => 'No autenticado.'
            ], 401);
        }

        // Cargar el rol del usuario si no está cargado en memoria
        if (! $user->relationLoaded('role')) {
            $user->load('role');
        }

        $role = $user->role;

        if (! $role) {
            return response()->json([
                'message' => 'Acceso denegado: El usuario no tiene un rol asignado.'
            ], 403);
        }

        $permisos = $role->permisos_json ?? [];

        // 1. Acceso total: Si es SUPER_ADMIN o tiene el comodín 'all' => true
        if ($role->nombre === 'SUPER_ADMIN' || ! empty($permisos['all'])) {
            return $next($request);
        }

        // 2. Control de Permiso Específico
        if (empty($permisos[$permission]) || $permisos[$permission] !== true) {
            return response()->json([
                'message' => "Acceso denegado: No posees el permiso '{$permission}' para realizar esta acción."
            ], 403);
        }

        return $next($request);
    }
}