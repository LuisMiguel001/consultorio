<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Evolucion extends Model
{
    protected $fillable = [
        'consulta_id',
        'nota',
        'plan'
    ];

    public function consulta()
    {
        return $this->belongsTo(Consulta::class);
    }
}
