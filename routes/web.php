<?php

use App\Http\Controllers\Api\PagoController;
use App\Http\Controllers\Api\PagoReportadoController;
use App\Http\Controllers\Api\CierreCajaController;
use App\Http\Controllers\Api\ClienteController;
use App\Http\Controllers\Api\PosController;
use App\Models\Cliente;
use App\Models\Plan;
use App\Models\Venta;
use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\DB;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Http\Controllers\ExportController;
use App\Http\Controllers\SuperAdmin\EmpresaController;


/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
*/
Route::middleware(['auth'])->prefix('superadmin')->group(function () {
    Route::get('/empresas', [EmpresaController::class, 'index'])->name('superadmin.empresas.index');

    Route::get('/empresas/crear', function () {
        return view('superadmin.empresas.create');
    })->name('superadmin.empresas.create');

    Route::post('/empresas', [EmpresaController::class, 'store'])->name('superadmin.empresas.store');
    Route::post('/empresas/{id}/toggle', [EmpresaController::class, 'toggle'])->name('superadmin.empresas.toggle');

});



Route::post('/logout', function (Request $request) {
    // 1. Si usa autenticación web por sesión
    Auth::guard('web')->logout();

    // 2. Invalidar sesión y regenerar token CSRF
    $request->session()->invalidate();
    $request->session()->regenerateToken();

    // 3. Si usaba token de Sanctum guardado en sesión, limpiarlo
    if ($request->hasSession()) {
        $request->session()->forget('sanctum_token');
    }

    return redirect('/login')->with('success', 'Has cerrado sesión exitosamente.');
})->name('logout');


Route::get('/', function () { return view('welcome'); });

Route::get('/login', function () { return redirect()->route('login');});

Route::middleware(['auth'])->group(function () {

    // Dashboard Principal
    Route::get('/dashboard', function () {
        return view('dashboard');
    })->name('dashboard');

    /*
    |--------------------------------------------------------------------------
    | Operaciones de Administración
    |--------------------------------------------------------------------------
    */
    
    /*
    |--------------------------------------------------------------------------
    | Operaciones de Recepción y Venta
    |--------------------------------------------------------------------------
    */
    // Check-in / Molinete
    Route::get('/checkin', function () {
        return view('checkin.index');
    })->name('checkin.index');

    // Punto de Venta (POS)
    Route::get('/pos', function () {
        $productos = DB::table('productos')
            ->join('inventario_sedes', 'productos.id', '=', 'inventario_sedes.producto_id')
            ->where('inventario_sedes.sede_id', auth()->user()->sede_id)
            ->select('productos.*', 'inventario_sedes.stock_actual')
            ->get();

        return view('pos.index', compact('productos'));
    })->name('pos.index');

    // Impresión de Ticket Térmico POS
    Route::get('/tickets/venta/{id}/imprimir', function (string $id) {
        $venta = Venta::with(['empresa', 'sede', 'vendedor', 'cliente', 'detalles.producto'])->findOrFail($id);
        return view('tickets.venta', compact('venta'));
    })->name('tickets.imprimir');

    // Portal del Atleta
    Route::get('/portal', function () {
        $cliente = auth()->user()->cliente;

        if (! $cliente) {
            return redirect()->route('dashboard')->with('error', 'Tu cuenta no tiene un perfil de atleta vinculado.');
        }

        return view('portal.index', compact('cliente'));
    })->name('portal.index');
    /*
    |--------------------------------------------------------------------------
    | Arqueo y Control de Caja
    |--------------------------------------------------------------------------
    */
    Route::prefix('admin/caja')->group(function () {
        Route::get('/', [CierreCajaController::class, 'index'])->name('caja.index');
        Route::post('/aperturar', [CierreCajaController::class, 'aperturar'])->name('caja.aperturar');
        Route::post('/cerrar', [CierreCajaController::class, 'cerrar'])->name('caja.cerrar');
    });

    Route::prefix('admin/pagos')->group(function () {
        Route::get('/conciliacion', [PagoReportadoController::class, 'index'])->name('pagos.conciliacion');
        Route::post('/reportado/{id}/aprobar', [PagoReportadoController::class, 'aprobar'])->name('admin.pagos.aprobar');
        Route::post('/reportado/{id}/rechazar', [PagoReportadoController::class, 'rechazar'])->name('admin.pagos.rechazar');
    });

    /*
    |--------------------------------------------------------------------------
    | Gestión de Atletas y Pagos
    |--------------------------------------------------------------------------
    */
    Route::get('/clientes', function () {
        $clientes = Cliente::where('empresa_id', auth()->user()->empresa_id)->get();
        $planes = Plan::where('empresa_id', auth()->user()->empresa_id)->where('activo', true)->get();

        return view('clientes.index', compact('clientes', 'planes'));
    })->name('clientes.index');
    Route::post('/clientes/store', [ClienteController::class, 'store'])->name('clientes.store');
    // Renovación Express / Registro de Pago desde Blade
    Route::post('/pagos/store', [PagoController::class, 'store'])->name('pagos.store');

});



Route::middleware(['auth'])->prefix('admin/exportar')->group(function () {
    Route::get('/caja/{formato?}', [ExportController::class, 'exportarCierresCaja'])->name('export.caja');
    Route::get('/atletas-vencidos/{formato?}', [ExportController::class, 'exportarAtletasVencidos'])->name('export.vencidos');
});

require __DIR__.'/auth.php';
