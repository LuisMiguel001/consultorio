@auth
    @if(!auth()->user()->hasRole('admin') && auth()->user()->consultorio)
        @php
            $consultorio = auth()->user()->consultorio;
            $uso = $consultorio->usoRecursoActual();
            $plan = $consultorio->planActual();
        @endphp

        @if($uso && $plan)
            <div class="card mb-3">
                <div class="card-body">
                    <h6 class="mb-3">📊 \Uso de Recursos - Plan {{ $plan->nombre }}</h6>

                    @if($plan->max_pacientes)
                        <div class="mb-2">
                            <small class="text-muted">Pacientes:</small>
                            <div class="progress" style="height: 20px;">
                                <div class="progress-bar" style="width: {{ ($uso->pacientes_registrados / $plan->max_pacientes) * 100 }}%">
                                    {{ $uso->pacientes_registrados }} / {{ $plan->max_pacientes }}
                                </div>
                            </div>
                        </div>
                    @endif

                    @if($plan->max_citas)
                        <div class="mb-2">
                            <small class="text-muted">Citas:</small>
                            <div class="progress" style="height: 20px;">
                                <div class="progress-bar bg-info" style="width: {{ ($uso->citas_creadas / $plan->max_citas) * 100 }}%">
                                    {{ $uso->citas_creadas }} / {{ $plan->max_citas }}
                                </div>
                            </div>
                        </div>
                    @endif

                    @if($plan->max_mensajes_whatsapp)
                        <div class="mb-2">
                            <small class="text-muted">WhatsApp:</small>
                            <div class="progress" style="height: 20px;">
                                <div class="progress-bar bg-success" style="width: {{ ($uso->mensajes_whatsapp_enviados / $plan->max_mensajes_whatsapp) * 100 }}%">
                                    {{ $uso->mensajes_whatsapp_enviados }} / {{ $plan->max_mensajes_whatsapp }}
                                </div>
                            </div>
                        </div>
                    @endif
                </div>
            </div>
        @endif
    @endif
@endauth
