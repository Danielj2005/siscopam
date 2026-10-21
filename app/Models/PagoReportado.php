<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class PagoReportado extends Model
{
    use HasUuids;

    protected $table = 'pagos_reportados';

    protected $fillable = [
        'empresa_id',
        'sede_id',
        'cliente_id',
        'monto_usd',
        'monto_ves',
        'tasa_bcv',
        'metodo_pago',
        'banco_origen',
        'banco_destino',
        'numero_referencia',
        'comprobante_path',
        'fecha_pago',
        'dias_renovacion',
        'estatus',
        'motivo_rechazo',
        'aprobado_por_user_id',
        'fecha_revision',
    ];

    protected $casts = [
        'monto_usd' => 'decimal:2',
        'monto_ves' => 'decimal:2',
        'tasa_bcv' => 'decimal:4',
        'fecha_pago' => 'date',
        'fecha_revision' => 'datetime',
    ];

    public function cliente(): BelongsTo
    {
        return $this->belongsTo(Cliente::class);
    }

    public function sede(): BelongsTo
    {
        return $this->belongsTo(Sede::class);
    }

    public function revisor(): BelongsTo
    {
        return $this->belongsTo(User::class, 'aprobado_por_user_id');
    }
}