<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Empresa extends Model
{
    use HasUuids;

    protected $fillable = [
        'nombre',
        'rif',
        'configuracion',
    ];

    protected $casts = [
        'configuracion' => 'array',
    ];
    /**
     * Obtiene todas las sedes asociadas a la empresa.
     */
    public function sedes(): HasMany
    {
        return $this->hasMany(Sede::class, 'empresa_id');
    }
}