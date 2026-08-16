@extends('layouts.app')

@section('content')
    <div class="container">

        <style>
            .comparar-header {
                background: #0d47a1;
                color: white;
                border-radius: 16px;
                padding: 16px 20px;
                margin-bottom: 20px;
            }

            .comparar-header h6 {
                margin: 0;
                font-weight: 600;
            }

            .comparar-col {
                background: #f4f8fd;
                border-radius: 16px;
                box-shadow: 0 8px 20px rgba(0, 0, 0, .08);
                padding: 16px;
                height: 100%;
            }

            .comparar-col-title {
                font-weight: 600;
                color: #0d47a1;
                font-size: 15px;
                margin-bottom: 12px;
                text-align: center;
                text-transform: uppercase;
                letter-spacing: .5px;
            }

            .comparar-foto-item {
                margin-bottom: 14px;
                position: relative;
            }

            .comparar-foto {
                width: 100%;
                border-radius: 10px;
                object-fit: cover;
                aspect-ratio: 1 / 1;
                cursor: pointer;
                border: 1px solid #e3ecf7;
                display: block;
            }

            .comparar-btn-anotar {
                position: absolute;
                top: 8px;
                right: 8px;
                background: rgba(13, 71, 161, .85);
                color: white;
                border: none;
                border-radius: 6px;
                font-size: 12px;
                padding: 4px 8px;
                cursor: pointer;
            }

            .comparar-btn-anotar:hover {
                background: #0d47a1;
            }

            .comparar-foto-fecha {
                font-size: 12px;
                color: #6c8bb5;
                margin-top: 4px;
                text-align: center;
            }

            .comparar-badge-anotada {
                position: absolute;
                bottom: 8px;
                left: 8px;
                font-size: 10px;
            }

            .comparar-vacio {
                color: #6c8bb5;
                text-align: center;
                padding: 30px 10px;
                font-size: 14px;
            }
        </style>

        <div class="comparar-header d-flex justify-content-between align-items-center flex-wrap">
            <h6>🔍 Comparación Antes / Después</h6>
            <a href="{{ route('consultas.show', $dermatologia->consulta_id) }}"
                class="btn btn-sm btn-light">
                ← Volver a la consulta
            </a>
        </div>

        <div class="row">

            <!-- ANTES -->
            <div class="col-md-6 mb-4">
                <div class="comparar-col">
                    <div class="comparar-col-title">Antes</div>

                    @forelse ($fotosAntes as $foto)
                        <div class="comparar-foto-item">
                            <img src="{{ $foto->ruta_anotada ? $foto->url_anotada : $foto->url }}"
                                class="comparar-foto"
                                data-bs-toggle="modal" data-bs-target="#verFotoComparar{{ $foto->id }}"
                                alt="{{ $foto->descripcion }}">

                            <button type="button" class="comparar-btn-anotar foto-anotable"
                                data-foto-id="{{ $foto->id }}"
                                data-foto-url="{{ $foto->url }}">
                                ✏️ Anotar
                            </button>

                            @if ($foto->ruta_anotada)
                                <span class="badge bg-info comparar-badge-anotada">Anotada</span>
                            @endif

                            <div class="comparar-foto-fecha">
                                {{ $foto->created_at->format('d/m/Y H:i') }}
                                @if ($foto->descripcion)
                                    — {{ $foto->descripcion }}
                                @endif
                            </div>
                        </div>

                        <div class="modal fade" id="verFotoComparar{{ $foto->id }}" tabindex="-1">
                            <div class="modal-dialog modal-lg modal-dialog-centered">
                                <div class="modal-content">
                                    <div class="modal-header">
                                        <span class="badge bg-primary">Antes</span>
                                        <small class="text-muted ms-2">
                                            {{ $foto->created_at->format('d/m/Y H:i') }}
                                        </small>
                                        <button type="button" class="btn-close ms-auto"
                                            data-bs-dismiss="modal"></button>
                                    </div>
                                    <img src="{{ $foto->ruta_anotada ? $foto->url_anotada : $foto->url }}" class="img-fluid">
                                    @if ($foto->descripcion)
                                        <div class="modal-body">
                                            <p class="mb-0">{{ $foto->descripcion }}</p>
                                        </div>
                                    @endif
                                </div>
                            </div>
                        </div>
                    @empty
                        <div class="comparar-vacio">
                            No hay fotos registradas en "Antes".
                        </div>
                    @endforelse
                </div>
            </div>

            <!-- DESPUÉS -->
            <div class="col-md-6 mb-4">
                <div class="comparar-col">
                    <div class="comparar-col-title">Después</div>

                    @forelse ($fotosDespues as $foto)
                        <div class="comparar-foto-item">
                            <img src="{{ $foto->ruta_anotada ? $foto->url_anotada : $foto->url }}"
                                class="comparar-foto"
                                data-bs-toggle="modal" data-bs-target="#verFotoComparar{{ $foto->id }}"
                                alt="{{ $foto->descripcion }}">

                            <button type="button" class="comparar-btn-anotar foto-anotable"
                                data-foto-id="{{ $foto->id }}"
                                data-foto-url="{{ $foto->url }}">
                                ✏️ Anotar
                            </button>

                            @if ($foto->ruta_anotada)
                                <span class="badge bg-info comparar-badge-anotada">Anotada</span>
                            @endif

                            <div class="comparar-foto-fecha">
                                {{ $foto->created_at->format('d/m/Y H:i') }}
                                @if ($foto->descripcion)
                                    — {{ $foto->descripcion }}
                                @endif
                            </div>
                        </div>

                        <div class="modal fade" id="verFotoComparar{{ $foto->id }}" tabindex="-1">
                            <div class="modal-dialog modal-lg modal-dialog-centered">
                                <div class="modal-content">
                                    <div class="modal-header">
                                        <span class="badge bg-success">Después</span>
                                        <small class="text-muted ms-2">
                                            {{ $foto->created_at->format('d/m/Y H:i') }}
                                        </small>
                                        <button type="button" class="btn-close ms-auto"
                                            data-bs-dismiss="modal"></button>
                                    </div>
                                    <img src="{{ $foto->ruta_anotada ? $foto->url_anotada : $foto->url }}" class="img-fluid">
                                    @if ($foto->descripcion)
                                        <div class="modal-body">
                                            <p class="mb-0">{{ $foto->descripcion }}</p>
                                        </div>
                                    @endif
                                </div>
                            </div>
                        </div>
                    @empty
                        <div class="comparar-vacio">
                            No hay fotos registradas en "Después".
                        </div>
                    @endforelse
                </div>
            </div>

        </div>

    </div>

    @include('dermatologia.partials.editor-anotacion')
@endsection
