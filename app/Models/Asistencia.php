<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Asistencia extends Model
{
    use HasUuids;

    protected $table = 'asistencias';

    protected $fillable = [
        'empresa_id',
        'sede_id',
        'cliente_id',
        'metodo_validacion',
        'estatus',
        'detalles_dispositivo_json',
        'fecha_hora',
    ];

    protected $casts = [
        'detalles_dispositivo_json' => 'array',
        'fecha_hora' => 'datetime',
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
}