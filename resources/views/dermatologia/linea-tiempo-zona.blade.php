@extends('layouts.app')

@section('content')
    <div class="container">

        <style>
            .lt-header {
                background: #0d47a1;
                color: white;
                border-radius: 16px;
                padding: 16px 20px;
                margin-bottom: 20px;
            }

            .lt-consulta-card {
                background: #f4f8fd;
                border-radius: 16px;
                box-shadow: 0 8px 20px rgba(0, 0, 0, .08);
                padding: 16px;
                margin-bottom: 20px;
            }

            .lt-fecha {
                font-weight: 600;
                color: #0d47a1;
                font-size: 15px;
                margin-bottom: 4px;
            }

            .lt-etiqueta-tipo {
                font-size: 12px;
                color: #6c8bb5;
                margin-bottom: 12px;
            }

            .lt-fotos-row {
                display: flex;
                gap: 10px;
                flex-wrap: wrap;
            }

            .lt-etapa-col {
                flex: 1;
                min-width: 140px;
            }

            .lt-etapa-titulo {
                font-size: 11px;
                text-transform: uppercase;
                letter-spacing: .5px;
                color: #6c8bb5;
                margin-bottom: 6px;
                text-align: center;
            }

            .lt-foto {
                width: 100%;
                aspect-ratio: 1/1;
                object-fit: cover;
                border-radius: 8px;
                border: 1px solid #e3ecf7;
                cursor: pointer;
                margin-bottom: 6px;
            }

            .lt-sin-fotos {
                font-size: 12px;
                color: #b0bfd0;
                text-align: center;
                padding: 20px 0;
                border: 1px dashed #d5e2f0;
                border-radius: 8px;
            }

            .lt-detalle {
                font-size: 13px;
                color: #1a2b3c;
                margin-top: 10px;
                padding-top: 10px;
                border-top: 1px solid #e3ecf7;
            }
        </style>

        <div class="lt-header d-flex justify-content-between align-items-center flex-wrap">
            <div>
                <h6 class="mb-1">Línea de Tiempo — {{ $zona->nombre }}</h6>
                <small>{{ $paciente->nombre }} {{ $paciente->apellido }} · {{ $consultasDerm->count() }} consulta(s) registradas en esta zona</small>
            </div>
            <a href="{{ route('pacientes.show', $paciente->id) }}" class="btn btn-sm btn-light">
                ← Volver al paciente
            </a>
        </div>

        @forelse ($consultasDerm as $cd)
            <div class="lt-consulta-card">
                <div class="lt-fecha">
                    {{ \Carbon\Carbon::parse($cd->consulta->fecha_consulta)->format('d/m/Y h:i A') }}
                </div>
                <div class="lt-etiqueta-tipo">
                    {{ $cd->consulta->tipo_consulta }}
                    @if ($cd->diagnostico_dermatologico)
                        · {{ $cd->diagnostico_dermatologico }}
                    @endif
                </div>

                <div class="lt-fotos-row">
                    @foreach (['antes' => 'Antes', 'durante' => 'Durante', 'despues' => 'Después'] as $key => $label)
                        <div class="lt-etapa-col">
                            <div class="lt-etapa-titulo">{{ $label }}</div>

                            @php $fotosEtapa = $cd->fotos->where('etapa', $key); @endphp

                            @forelse ($fotosEtapa as $foto)
                                <img src="{{ $foto->url }}" class="lt-foto"
                                    data-bs-toggle="modal" data-bs-target="#ltFoto{{ $foto->id }}">

                                <div class="modal fade" id="ltFoto{{ $foto->id }}" tabindex="-1">
                                    <div class="modal-dialog modal-lg modal-dialog-centered">
                                        <div class="modal-content">
                                            <div class="modal-header">
                                                <span class="badge bg-primary">{{ $label }}</span>
                                                <small class="text-muted ms-2">
                                                    {{ \Carbon\Carbon::parse($cd->consulta->fecha_consulta)->format('d/m/Y h:i A') }}
                                                </small>
                                                <button type="button" class="btn-close ms-auto" data-bs-dismiss="modal"></button>
                                            </div>
                                            <img src="{{ $foto->url }}" class="img-fluid">
                                        </div>
                                    </div>
                                </div>
                            @empty
                                <div class="lt-sin-fotos">Sin foto</div>
                            @endforelse
                        </div>
                    @endforeach
                </div>

                <div class="lt-detalle">
                    @if ($cd->lesion_tamano)<strong>Tamaño:</strong> {{ $cd->lesion_tamano }} · @endif
                    @if ($cd->lesion_color)<strong>Color:</strong> {{ $cd->lesion_color }} · @endif
                    @if ($cd->lesion_localizacion)<strong>Detalle:</strong> {{ $cd->lesion_localizacion }}@endif
                </div>

                <a href="{{ route('consultas.show', $cd->consulta_id) }}" class="btn btn-sm btn-outline-primary mt-3">
                    Ver consulta completa
                </a>
            </div>
        @empty
            <div class="alert alert-secondary">
                No hay consultas registradas para esta zona todavía.
            </div>
        @endforelse

    </div>
@endsection
