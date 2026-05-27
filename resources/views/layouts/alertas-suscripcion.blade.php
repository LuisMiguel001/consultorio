@if(session('warning'))
    <div class="alert alert-warning alert-dismissible fade show" role="alert">
        <strong>⚠️ Atención:</strong> {{ session('warning') }}
        <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
    </div>
@endif

@auth
    @if(!auth()->user()->hasRole('admin') && auth()->user()->consultorio)
        @php
            $estado = auth()->user()->consultorio->estadoSuscripcion();
        @endphp

        @if(in_array($estado['estado'], ['sin_suscripcion', 'expirada', 'critico']))
            <div class="alert alert-{{ $estado['clase'] }} alert-dismissible fade show" role="alert">
                <strong>{{ $estado['icono'] }} {{ $estado['mensaje'] }}</strong>
                @if($estado['estado'] === 'critico')
                    - Por favor renueva tu suscripción antes de que expire.
                @endif
                <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
            </div>
        @endif
    @endif
@endauth
