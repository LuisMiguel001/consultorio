<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ConsultaDermatologica extends Model
{
    protected $fillable = [
        'consulta_id',
        'fototipo_fitzpatrick',
        'tipo_piel',
        'motivo_dermatologico',
        'lesion_tipo',
        'zona_corporal_id',
        'lesion_localizacion',
        'lesion_tamano',
        'lesion_color',
        'lesion_bordes',
        'lesion_superficie',
        'tiempo_evolucion',
        'prurito',
        'dolor',
        'ardor',
        'sangrado',
        'sintomas_asociados_otros',
        'dermatoscopia_realizada',
        'hallazgos_dermatoscopia',
        'biopsia_realizada',
        'resultado_biopsia',
        'antecedentes_dermatologicos',
        'alergias_cutaneas',
        'exposicion_solar',
        'diagnostico_dermatologico',
        'tratamiento_topico',
        'tratamiento_sistemico',
        'es_procedimiento_estetico',
        'procedimiento_estetico',
        'zona_tratada',
        'producto_utilizado',
        'cantidad_aplicada',
        'tecnica_aplicacion',
        'efectos_secundarios_esperados',
        'consentimiento_informado',
        'fecha_proxima_sesion',
        'recomendaciones_post_procedimiento',
    ];

    protected $casts = [
        'prurito' => 'boolean',
        'dolor' => 'boolean',
        'ardor' => 'boolean',
        'sangrado' => 'boolean',
        'dermatoscopia_realizada' => 'boolean',
        'biopsia_realizada' => 'boolean',
        'es_procedimiento_estetico' => 'boolean',
        'consentimiento_informado' => 'boolean',
        'fecha_proxima_sesion' => 'date',
    ];

    public function consulta()
    {
        return $this->belongsTo(Consulta::class);
    }

    public function fotos()
    {
        return $this->hasMany(ProcedimientoFoto::class)->orderBy('orden');
    }

    public function fotosAntes()
    {
        return $this->fotos()->where('etapa', 'antes');
    }

    public function fotosDurante()
    {
        return $this->fotos()->where('etapa', 'durante');
    }

    public function fotosDespues()
    {
        return $this->fotos()->where('etapa', 'despues');
    }

    public function zonaCorporal()
    {
        return $this->belongsTo(ZonaCorporal::class);
    }
}
