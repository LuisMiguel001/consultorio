<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <title>Receta Médica</title>

    <style>
        body {
            font-family: DejaVu Sans, sans-serif;
            font-size: 13px;
        }

        .header {
            text-align: center;
            margin-bottom: 20px;
        }

        .titulo {
            font-size: 20px;
            font-weight: bold;
        }

        .datos {
            margin-bottom: 15px;
        }

        table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 15px;
        }

        th, td {
            border: 1px solid #000;
            padding: 6px;
        }

        .firma {
            margin-top: 50px;
            text-align: center;
        }
    </style>
</head>
<body>

<div class="header">
    <div class="titulo">RECETA MÉDICA</div>
</div>

<div class="datos">
    <strong>Paciente:</strong>
    {{ $consulta->paciente->nombre }}
    {{ $consulta->paciente->apellido }}
    <br>

    <strong>Fecha:</strong>
    {{ $consulta->created_at->format('d/m/Y') }}
</div>

<table>
    <thead>
        <tr>
            <th>Medicamento</th>
            <th>Dosis</th>
            <th>Frecuencia</th>
            <th>Duración</th>
            <th>Vía</th>
        </tr>
    </thead>
    <tbody>
        @foreach($consulta->tratamientos as $med)
        <tr>
            <td>{{ $med->medicamento }}</td>
            <td>{{ $med->dosis }}</td>
            <td>{{ $med->frecuencia }}</td>
            <td>{{ $med->duracion }}</td>
            <td>{{ ucfirst($med->via_administracion) }}</td>
        </tr>
        @endforeach
    </tbody>
</table>

<div class="firma">
    _______________________________<br>
    Firma y Sello Médico
</div>

</body>
</html>
