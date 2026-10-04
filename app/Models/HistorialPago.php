<?php
namespace App\Models;

use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class HistorialPago extends Model
{
    use HasUuids;

    protected $table = 'historial_pagos';

    protected $fillable = [
        'empresa_id',
        'sede_id',
        'cliente_id',
        'plan_id',
        'usuario_registro_id',
        'monto_usd',
        'monto_ves',
        'tasa_bcv_usada',
        'metodo_pago',
        'referencia',
        'banco_origen',
        'banco_destino',
        'estatus',
        'notas',
    ];

    protected $casts = [
        'monto_usd' => 'decimal:2',
        'monto_ves' => 'decimal:2',
        'tasa_bcv_usada' => 'decimal:4',
    ];

    public function cliente(): BelongsTo
    {
        return $this->belongsTo(Cliente::class);
    }

    public function plan(): BelongsTo
    {
        return $this->belongsTo(Plan::class);
    }

    public function usuarioRegistro(): BelongsTo
    {
        return $this->belongsTo(User::class, 'usuario_registro_id');
    }
}