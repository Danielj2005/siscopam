<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Sede extends Model
{
    use HasUuids;

    protected $table = 'sedes';

    protected $fillable = [
        'empresa_id',
        'nombre',
        'direccion',
        'horarios_json',
    ];

    protected $casts = [
        'horarios_json' => 'array',
    ];

    /**
     * Obtiene la empresa (Tenant) a la que pertenece esta sede.
     */
    public function empresa(): BelongsTo
    {
        return $this->belongsTo(Empresa::class, 'empresa_id');
    }
}