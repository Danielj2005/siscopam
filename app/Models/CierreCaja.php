<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class CierreCaja extends Model
{
    use HasUuids;

    protected $table = 'cierres_caja';

    protected $fillable = [
        'empresa_id',
        'sede_id',
        'user_id',
        'fecha_apertura',
        'fecha_cierre',
        'monto_inicial_usd',
        'monto_inicial_ves',
        'sistema_efectivo_usd',
        'sistema_efectivo_ves',
        'sistema_pago_movil_ves',
        'sistema_punto_ves',
        'sistema_zelle_usd',
        'declarado_efectivo_usd',
        'declarado_efectivo_ves',
        'declarado_pago_movil_ves',
        'declarado_punto_ves',
        'declarado_zelle_usd',
        'diferencia_usd',
        'estatus',
        'observaciones',
    ];

    protected $casts = [
        'fecha_apertura' => 'datetime',
        'fecha_cierre' => 'datetime',
        'monto_inicial_usd' => 'decimal:2',
        'monto_inicial_ves' => 'decimal:2',
        'sistema_efectivo_usd' => 'decimal:2',
        'sistema_efectivo_ves' => 'decimal:2',
        'sistema_pago_movil_ves' => 'decimal:2',
        'sistema_punto_ves' => 'decimal:2',
        'sistema_zelle_usd' => 'decimal:2',
        'declarado_efectivo_usd' => 'decimal:2',
        'declarado_efectivo_ves' => 'decimal:2',
        'declarado_pago_movil_ves' => 'decimal:2',
        'declarado_punto_ves' => 'decimal:2',
        'declarado_zelle_usd' => 'decimal:2',
        'diferencia_usd' => 'decimal:2',
    ];

    public function sede(): BelongsTo
    {
        return $this->belongsTo(Sede::class);
    }

    public function cajero(): BelongsTo
    {
        return $this->belongsTo(User::class, 'user_id');
    }
}