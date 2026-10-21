<?php

use App\Http\Controllers\ProfileController;
use Illuminate\Support\Facades\Route;
use App\Models\Venta;
use App\Http\Controllers\CierreCajaController;





/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
*/

Route::get('/', function () { return view('welcome'); });

/*
|--------------------------------------------------------------------------
| Admin Routes
|--------------------------------------------------------------------------
*/

Route::get('/dashboard', function () {
    return view('dashboard');
})->middleware(['auth', 'verified'])->name('dashboard');

Route::middleware(['auth'])->prefix('admin/caja')->group(function () {
    Route::get('/', [CierreCajaController::class, 'index'])->name('caja.index');
    Route::post('/aperturar', [CierreCajaController::class, 'aperturar'])->name('caja.aperturar');
    Route::post('/cerrar', [CierreCajaController::class, 'cerrar'])->name('caja.cerrar');
});
/*
|--------------------------------------------------------------------------
| Private Routes
|--------------------------------------------------------------------------
*/

Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
});

/*
|--------------------------------------------------------------------------
| POS Routes
|--------------------------------------------------------------------------
*/

Route::get('/tickets/venta/{id}/imprimir', function (string $id) {
    $venta = Venta::with(['empresa', 'sede', 'vendedor', 'cliente', 'detalles.producto'])->findOrFail($id);
    return view('tickets.venta', compact('venta'));
});


require __DIR__.'/auth.php';
