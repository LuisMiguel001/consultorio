<form action="{{ route('pacientes.store') }}" method="POST">
    @csrf

    <h3>Datos Personales</h3>

    <input type="text" name="nombre" placeholder="Nombre">
    <input type="text" name="apellido" placeholder="Apellido">
    <input type="text" name="cedula" placeholder="Cédula">
    <input type="date" name="fecha_nacimiento">

    <select name="sexo">
        <option value="">Seleccione sexo</option>
        <option value="Masculino">Masculino</option>
        <option value="Femenino">Femenino</option>
    </select>

    <input type="text" name="telefono" placeholder="Teléfono">
    <input type="email" name="email" placeholder="Email">
    <input type="text" name="direccion" placeholder="Dirección">

    <h3>Seguro Médico</h3>

    <input type="text" name="seguro_medico" placeholder="Nombre del Seguro">
    <input type="text" name="nss" placeholder="Número de Seguridad Social">

    <h3>Información Médica</h3>

    <input type="text" name="tipo_sangre" placeholder="Tipo de Sangre">
    <input type="text" name="estado_civil" placeholder="Estado Civil">
    <input type="text" name="contactoEmergencia" placeholder="Contacto de Emergencia">

    <button type="submit">Guardar</button>
</form>
