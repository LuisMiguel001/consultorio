<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Plan extends Model
{
    use HasFactory;

    protected $fillable = [
        'nombre',
        'descripcion',
        'precio_mensual',
        'precio_anual',
        'max_doctores',
        'max_secretarias',
        'max_enfermeras',
        'max_pacientes',
        'caracteristicas',
        'activo',
    ];

    protected $casts = [
        'precio_mensual' => 'decimal:2',
        'precio_anual' => 'decimal:2',
        'caracteristicas' => 'array',
        'activo' => 'boolean',
    ];

    // Relaciones
    public function suscripciones()
    {
        return $this->hasMany(Suscripcion::class);
    }
}
