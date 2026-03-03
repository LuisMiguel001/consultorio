<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Diagnostico extends Model
{
    protected $fillable = [
        'consulta_id',
        'diagnostico',
        'tipo',
        'codigo_cie10',
    ];

    public function consulta()
    {
        return $this->belongsTo(Consulta::class);
    }
}
