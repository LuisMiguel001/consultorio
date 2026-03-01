@extends('layout')

@section('content')

<h3>Listado de Citas</h3>

<a href="{{ route('agenda.citas.create') }}" class="btn btn-success mb-3">
    Nueva Cita
</a>

<table class="table table-bordered">
    <thead>
        <tr>
            <th>Paciente</th>
            <th>Fecha</th>
            <th>Hora</th>
            <th>Estado</th>
        </tr>
    </thead>
    <tbody>
        @foreach($citas as $cita)
        <tr>
            <td>{{ $cita->paciente->nombre }} {{ $cita->paciente->apellido }}</td>
            <td>{{ $cita->fecha }}</td>
            <td>{{ $cita->hora }}</td>
            <td>{{ $cita->estado_cita }}</td>
        </tr>
        @endforeach
    </tbody>
</table>

@endsection
