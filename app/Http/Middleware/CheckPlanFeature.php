<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class CheckPlanFeature
{
    public function handle(Request $request, Closure $next, string $modulo): Response
    {
        $empresa = $request->user()->empresa;

        if (! $empresa) {
            abort(403, 'Empresa no asociada.');
        }

        // Si el plan es básico, restringir módulos avanzados (POS, Inventario, Arqueo)
        $plan = $empresa->plan ?? 'basico'; // 'basico' o 'completo'

        $modulosBasicos = ['pagos', 'clientes', 'checkin', 'portal'];

        if ($plan === 'basico' && ! in_array($modulo, $modulosBasicos)) {
            return redirect()->route('dashboard')->with('error', 'Tu plan básico no incluye acceso a este módulo. Actualiza a la versión completa.');
        }

        return $next($request);
    }
}