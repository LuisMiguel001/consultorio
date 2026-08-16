<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Storage;

class ProcedimientoFoto extends Model
{
    protected $fillable = [
        'consulta_dermatologica_id',
        'etapa',
        'ruta',
        'descripcion',
        'orden',
        'subido_por',
        'anotaciones',
        'ruta_anotada',
    ];

    public function consultaDermatologica()
    {
        return $this->belongsTo(ConsultaDermatologica::class);
    }

    public function subidoPor()
    {
        return $this->belongsTo(User::class, 'subido_por');
    }

    public function getUrlAttribute(): string
    {
        return Storage::url($this->ruta);
    }

    protected $casts = [
        'anotaciones' => 'array',
    ];

    public function getUrlAnotadaAttribute()
    {
        return $this->ruta_anotada ? Storage::url($this->ruta_anotada) : null;
    }
}
