<?php
namespace App\Models;

use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Plan extends Model
{
    use HasUuids;

    protected $table = 'planes';

    protected $fillable = [
        'empresa_id',
        'nombre',
        'precio_usd',
        'duracion_dias',
        'activo',
    ];

    protected $casts = [
        'precio_usd' => 'decimal:2',
        'duracion_dias' => 'integer',
        'activo' => 'boolean',
    ];

    public function empresa(): BelongsTo
    {
        return $this->belongsTo(Empresa::class);
    }
}