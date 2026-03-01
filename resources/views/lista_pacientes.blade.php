@extends('layouts.app')

@section('content')

<h4 class="mb-4">Lista de Pacientes</h4>

<table class="table table-bordered bg-white">
    <thead>
        <tr>
            <th>Nombre</th>
            <th>Cédula</th>
            <th>Teléfono</th>
            <th>ARS</th>
        </tr>
    </thead>
    <tbody>
        @foreach($pacientes as $paciente)
        <tr>
            <td>{{ $paciente->nombre }} {{ $paciente->apellido }}</td>
            <td>{{ $paciente->cedula }}</td>
            <td>{{ $paciente->telefono }}</td>
            <td>{{ $paciente->seguro_medico }}</td>
        </tr>
        @endforeach
    </tbody>
</table>

@endsection
