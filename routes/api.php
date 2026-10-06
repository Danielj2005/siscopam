<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Api\AuthController;
use App\Http\Controllers\Api\PagoController;
use App\Http\Controllers\Api\PosController;
use App\Services\BcvService;
use App\Http\Controllers\Api\CheckInController;



/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
*/

Route::get('/tasa-bcv', function (BcvService $bcvService) {
    return response()->json([
        'moneda' => 'USD',
        'tasa_ves' => $bcvService->obtenerTasaUsd(),
        'fecha_consulta' => now()->format('Y-m-d H:i:s'),
    ]);
});

Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
    return $request->user();
});


// Rutas públicas
Route::post('/login', [AuthController::class, 'login']);


Route::middleware(['auth:sanctum', 'permission:gestionar_planes'])->group(function () {
    // Endpoints protegidos
    Route::post('/pagos', [PagoController::class, 'store']);
    Route::get('/me', [AuthController::class, 'me']);
    Route::post('/logout', [AuthController::class, 'logout']);

    /*
    |--------------------------------------------------------------------------
    | Endpoints para el personal de Recepción / Molinete
    |--------------------------------------------------------------------------
    */

    Route::post('/checkin/qr', [CheckInController::class, 'escanearQr'])
        ->middleware('permission:procesar_checkin');

    Route::post('/checkin/manual', [CheckInController::class, 'checkInManual'])
        ->middleware('permission:procesar_checkin');

    Route::get('/checkin/historial/{sedeId}', [CheckInController::class, 'historial'])
        ->middleware('permission:ver_asistencias');

    // Endpoint para la App Móvil / Portal del Cliente
    Route::get('/cliente/mi-qr', [CheckInController::class, 'miCodigoQr']);

    // Obtener notificaciones del cliente logueado
    Route::get('/cliente/notificaciones', function (Request $request) {
        return response()->json([
            'no_leidas_count' => $request->user()->unreadNotifications->count(),
            'notificaciones' => $request->user()->notifications()->take(10)->get(),
        ]);
    });

    // Marcar notificaciones como leídas
    Route::post('/cliente/notificaciones/marcar-leidas', function (Request $request) {
        $request->user()->unreadNotifications->markAsRead();
        return response()->json(['message' => 'Notificaciones marcadas como leídas']);
    });
    /*
    |--------------------------------------------------------------------------
    | Consultar catálogo
    |--------------------------------------------------------------------------
    */
    // Consultar catálogo filtrado por sede con precios calculados en BS según BCV
    Route::get('/pos/catalogo/{sedeId}', [PosController::class, 'catalogoSede'])
        ->middleware('permission:ver_catalogo');



    /*
    |--------------------------------------------------------------------------
    | Punto de Venta
    |--------------------------------------------------------------------------
    */
    Route::post('/pos/ventas', [PosController::class, 'procesarVenta'])
        ->middleware('permission:registrar_ventas');
    Route::get('/pos/ventas/{id}/ticket', [PosController::class, 'obtenerTicket']);

});
