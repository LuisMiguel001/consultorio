<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Consultorio extends Model
{
    use HasFactory;

    protected $fillable = [
        'nombre',
        'direccion',
        'telefono',
        'email',
        'ruc',
        'descripcion',
        'activo',
    ];

    protected $casts = [
        'activo' => 'boolean',
    ];

    public function usuarios()
    {
        return $this->hasMany(User::class);
    }

    public function doctores()
    {
        return $this->hasMany(User::class)->role('doctor');
    }

    public function secretarias()
    {
        return $this->hasMany(User::class)->role('secretaria');
    }

    public function enfermeras()
    {
        return $this->hasMany(User::class)->role('enfermera');
    }

    public function suscripciones()
    {
        return $this->hasMany(Suscripcion::class);
    }

    public function suscripcionActiva()
    {
        return $this->hasOne(Suscripcion::class)
            ->where('estado', 'activa')
            ->where('fecha_fin', '>=', now())
            ->latest();
    }
}
