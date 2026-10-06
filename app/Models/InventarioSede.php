<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class InventarioSede extends Model
{
    use HasUuids;

    protected $table = 'inventario_sedes';

    protected $fillable = [
        'sede_id',
        'producto_id',
        'stock_actual',
    ];

    protected $casts = [
        'stock_actual' => 'integer',
    ];

    public function sede(): BelongsTo
    {
        return $this->belongsTo(Sede::class);
    }

    public function producto(): BelongsTo
    {
        return $this->belongsTo(Producto::class);
    }
}