<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Producto extends Model
{
    use HasUuids;

    protected $fillable = [
        'empresa_id',
        'nombre',
        'codigo_barras',
        'precio_usd',
        'costo_usd',
        'stock_minimo',
        'activo',
    ];

    protected $casts = [
        'precio_usd' => 'decimal:2',
        'costo_usd' => 'decimal:2',
        'stock_minimo' => 'integer',
        'activo' => 'boolean',
    ];

    public function empresa(): BelongsTo
    {
        return $this->belongsTo(Empresa::class);
    }

    public function inventarios(): HasMany
    {
        return $this->hasMany(InventarioSede::class);
    }

    public function detallesVenta(): HasMany
    {
        return $this->hasMany(DetalleVenta::class);
    }
}