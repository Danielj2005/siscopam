<?php
namespace App\Models;

use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Role extends Model
{
    use HasUuids;

    protected $fillable = [
        'nombre',
        'descripcion',
        'permisos_json',
    ];

    protected $casts = [
        'permisos_json' => 'array',
    ];

    public function usuarios(): HasMany
    {
        return $this->hasMany(User::class);
    }
}