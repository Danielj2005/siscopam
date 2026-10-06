<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Venta extends Model
{
    use HasUuids;

    protected $fillable = [
        'empresa_id',
        'sede_id',
        'cliente_id',
        'usuario_vendedor_id',
        'total_usd',
        'total_ves',
        'tasa_bcv_usada',
        'metodo_pago',
        'referencia',
    ];

    protected $casts = [
        'total_usd' => 'decimal:2',
        'total_ves' => 'decimal:2',
        'tasa_bcv_usada' => 'decimal:4',
    ];

    public function empresa(): BelongsTo
    {
        return $this->belongsTo(Empresa::class);
    }

    public function sede(): BelongsTo
    {
        return $this->belongsTo(Sede::class);
    }

    public function cliente(): BelongsTo
    {
        return $this->belongsTo(Cliente::class);
    }

    public function vendedor(): BelongsTo
    {
        return $this->belongsTo(User::class, 'usuario_vendedor_id');
    }

    public function detalles(): HasMany
    {
        return $this->hasMany(DetalleVenta::class);
    }
}