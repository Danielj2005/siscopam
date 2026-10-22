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
use Illuminate\Support\Facades\Auth;
use App\Http\Controllers\ExportController;
use App\Http\Controllers\SuperAdmin\EmpresaController;

Route::get('/', function () {
    return view('welcome');
});

Route::get('/login', function () {
    return redirect()->route('login');
});

/*
|--------------------------------------------------------------------------
| SuperAdmin
|--------------------------------------------------------------------------
*/
Route::middleware(['auth', 'superadmin'])->prefix('superadmin')->group(function () {
    Route::get('/dashboard', function () {
        return view('superadmin.index');
    })->name('superadmin.index');

    Route::get('/empresas', [EmpresaController::class, 'index'])->name('superadmin.empresas.index');
    Route::get('/empresas/crear', function () {
        return view('superadmin.empresas.create');
    })->name('superadmin.empresas.create');

    Route::post('/empresas', [EmpresaController::class, 'store'])->name('superadmin.empresas.store');
    Route::post('/empresas/{id}/toggle', [EmpresaController::class, 'toggle'])->name('superadmin.empresas.toggle');
});

/*
|--------------------------------------------------------------------------
| Admin/Gimnasio
|--------------------------------------------------------------------------
*/
Route::middleware(['auth'])->prefix('admin')->group(function () {
    Route::get('/dashboard', function () {
        return view('dashboard');
    })->name('dashboard');

    /*
    |--------------------------------------------------------------------------
    | Módulos base / plan básico
    |--------------------------------------------------------------------------
    */
    Route::get('/checkin', function () {
        return view('checkin.index');
    })->name('checkin.index');

    Route::get('/pagos/conciliacion', [PagoReportadoController::class, 'index'])->name('pagos.conciliacion');
    Route::post('/pagos/reportado/{id}/aprobar', [PagoReportadoController::class, 'aprobar'])->name('admin.pagos.aprobar');
    Route::post('/pagos/reportado/{id}/rechazar', [PagoReportadoController::class, 'rechazar'])->name('admin.pagos.rechazar');

    /*
    |--------------------------------------------------------------------------
    | Módulos premium / plan completo
    |--------------------------------------------------------------------------
    */
    Route::get('/pos', function () {
        $productos = DB::table('productos')
            ->join('inventario_sedes', 'productos.id', '=', 'inventario_sedes.producto_id')
            ->where('inventario_sedes.sede_id', auth()->user()->sede_id)
            ->select('productos.*', 'inventario_sedes.stock_actual')
            ->get();

        return view('pos.index', compact('productos'));
    })->name('pos.index');

    Route::get('/caja', [CierreCajaController::class, 'index'])->name('caja.index');
    Route::post('/caja/aperturar', [CierreCajaController::class, 'aperturar'])->name('caja.aperturar');
    Route::post('/caja/cerrar', [CierreCajaController::class, 'cerrar'])->name('caja.cerrar');

    Route::get('/exportar/caja/{formato?}', [ExportController::class, 'exportarCierresCaja'])->name('export.caja');
    Route::get('/exportar/atletas-vencidos/{formato?}', [ExportController::class, 'exportarAtletasVencidos'])->name('export.vencidos');

    /*
    |--------------------------------------------------------------------------
    | Ticket de venta
    |--------------------------------------------------------------------------
    */
    Route::get('/tickets/venta/{id}/imprimir', function (string $id) {
        $venta = Venta::with(['empresa', 'sede', 'vendedor', 'cliente', 'detalles.producto'])->findOrFail($id);
        return view('tickets.venta', compact('venta'));
    })->name('tickets.imprimir');
});

/*
|--------------------------------------------------------------------------
| Portal del atleta / cliente
|--------------------------------------------------------------------------
*/
Route::middleware(['auth'])->prefix('portal')->group(function () {
    Route::get('/', function () {
        $cliente = auth()->user()->cliente;

        if (! $cliente) {
            return redirect()->route('dashboard')->with('error', 'Tu cuenta no tiene un perfil de atleta vinculado.');
        }

        return view('portal.index', compact('cliente'));
    })->name('portal.index');

    Route::get('/pagos', function () {
        return view('portal.pagos');
    })->name('portal.pagos');

    Route::get('/membresia', function () {
        return view('portal.membresia');
    })->name('portal.membresia');
});

/*
|--------------------------------------------------------------------------
| Gestión de clientes / atletas del gimnasio
|--------------------------------------------------------------------------
*/
Route::middleware(['auth'])->group(function () {
    Route::get('/clientes', function () {
        $clientes = Cliente::where('empresa_id', auth()->user()->empresa_id)->get();
        $planes = Plan::where('empresa_id', auth()->user()->empresa_id)->where('activo', true)->get();

        return view('clientes.index', compact('clientes', 'planes'));
    })->name('clientes.index');

    Route::post('/clientes/store', [ClienteController::class, 'store'])->name('clientes.store');
    Route::post('/pagos/store', [PagoController::class, 'store'])->name('pagos.store');
});

require __DIR__.'/auth.php';