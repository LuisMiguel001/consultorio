@extends('layouts.app')

@section('content')

<div class="container">
    <h3>Antecedentes de {{ $paciente->nombre }}</h3>

    @if(session('success'))
        <div class="alert alert-success">
            {{ session('success') }}
        </div>
    @endif

    <form method="POST" action="/pacientes/{{ $paciente->id }}/antecedentes">
        @csrf

        <textarea name="antecedentes_personales" class="form-control mb-3" placeholder="Antecedentes personales"></textarea>

        <textarea name="antecedentes_familiares" class="form-control mb-3" placeholder="Antecedentes familiares"></textarea>

        <textarea name="antecedentes_quirurgicos" class="form-control mb-3" placeholder="Antecedentes quirúrgicos"></textarea>

        <textarea name="alergias" class="form-control mb-3" placeholder="Alergias"></textarea>

        <textarea name="medicamentos_actuales" class="form-control mb-3" placeholder="Medicamentos actuales"></textarea>

        <textarea name="habitos" class="form-control mb-3" placeholder="Hábitos (tabaco, alcohol, etc.)"></textarea>

        <button class="btn btn-primary w-100">Guardar Antecedentes</button>
    </form>

    <hr>

    <h5>Historial registrado</h5>

    @foreach($paciente->antecedentes as $ant)
        <div class="card mb-3">
            <div class="card-body">
                <small>Registrado por: {{ $ant->usuario->name }} | {{ $ant->created_at }}</small>
                <p><strong>Personales:</strong> {{ $ant->antecedentes_personales }}</p>
                <p><strong>Familiares:</strong> {{ $ant->antecedentes_familiares }}</p>
                <p><strong>Alergias:</strong> {{ $ant->alergias }}</p>
            </div>
        </div>
    @endforeach

</div>

@endsection
