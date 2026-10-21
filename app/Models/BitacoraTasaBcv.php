<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\Model;

class BitacoraTasaBcv extends Model
{
    use HasUuids;

    protected $table = 'bitacora_tasas_bcv';

    protected $fillable = [
        'tasa',
        'origen',
        'exitoso',
        'error_mensaje',
        'fecha_consulta',
    ];

    protected $casts = [
        'tasa' => 'decimal:4',
        'exitoso' => 'boolean',
        'fecha_consulta' => 'datetime',
    ];
}