<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ZonaCorporal extends Model
{
    protected $table = 'zonas_corporales';

    protected $fillable = ['nombre', 'grupo', 'orden', 'activo'];

    public function consultasDermatologicas()
    {
        return $this->hasMany(ConsultaDermatologica::class);
    }

    /**
     * Zonas agrupadas para poblar el <select> del formulario, ej:
     * ['Cabeza y cuello' => [...], 'Tronco' => [...], ...]
     */
    public static function agrupadas()
    {
        return static::where('activo', true)
            ->orderBy('orden')
            ->get()
            ->groupBy('grupo');
    }
}
