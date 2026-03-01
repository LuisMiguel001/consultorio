<input type="hidden" name="paciente_id" value="{{ $paciente->id ?? '' }}">

<!-- DATOS PERSONALES -->
<h6 class="text-uppercase fw-bold mb-3" style="color:#7a4ea0;">
    Datos personales
</h6>

<div class="row g-3">
    <div class="col-md-6">
        <label class="form-label">Nombre</label>
        <input type="text" name="nombre"
               class="form-control @error('nombre') is-invalid @enderror"
               value="{{ old('nombre', $paciente->nombre ?? '') }}">
        @error('nombre') <div class="invalid-feedback">{{ $message }}</div> @enderror
    </div>

    <div class="col-md-6">
        <label class="form-label">Apellido</label>
        <input type="text" name="apellido"
               class="form-control @error('apellido') is-invalid @enderror"
               value="{{ old('apellido', $paciente->apellido ?? '') }}">
        @error('apellido') <div class="invalid-feedback">{{ $message }}</div> @enderror
    </div>

    <div class="col-md-6">
        <label class="form-label">Cédula</label>
        <input type="text" name="cedula"
               class="form-control @error('cedula') is-invalid @enderror"
               value="{{ old('cedula', $paciente->cedula ?? '') }}"
               {{ isset($paciente) ? 'readonly' : '' }}>
        @error('cedula') <div class="invalid-feedback">{{ $message }}</div> @enderror
    </div>

    <div class="col-md-6">
        <label class="form-label">Fecha de nacimiento</label>
        <input type="date" name="fecha_nacimiento"
               class="form-control @error('fecha_nacimiento') is-invalid @enderror"
               value="{{ old('fecha_nacimiento', $paciente->fecha_nacimiento ?? '') }}">
        @error('fecha_nacimiento') <div class="invalid-feedback">{{ $message }}</div> @enderror
    </div>

    <div class="col-md-6">
        <label class="form-label">Sexo</label>
        <select name="sexo"
                class="form-select @error('sexo') is-invalid @enderror">
            <option value="">--Seleccione--</option>
            <option value="Masculino" {{ (old('sexo', $paciente->sexo ?? '') == 'Masculino') ? 'selected' : '' }}>Masculino</option>
            <option value="Femenino" {{ (old('sexo', $paciente->sexo ?? '') == 'Femenino') ? 'selected' : '' }}>Femenino</option>
            <option value="Otro" {{ (old('sexo', $paciente->sexo ?? '') == 'Otro') ? 'selected' : '' }}>Otro</option>
        </select>
        @error('sexo') <div class="invalid-feedback">{{ $message }}</div> @enderror
    </div>
</div>

<hr class="my-4">

<!-- CONTACTO -->
<h6 class="text-uppercase fw-bold mb-3" style="color:#7a4ea0;">
    Información de contacto
</h6>

<div class="row g-3">
    <div class="col-md-6">
        <label class="form-label">Teléfono</label>
        <input type="text" name="telefono"
               class="form-control"
               value="{{ old('telefono', $paciente->telefono ?? '') }}">
    </div>

    <div class="col-md-6">
        <label class="form-label">Correo electrónico</label>
        <input type="email" name="email"
               class="form-control"
               value="{{ old('email', $paciente->email ?? '') }}">
    </div>

    <div class="col-md-12">
        <label class="form-label">Dirección</label>
        <input type="text" name="direccion"
               class="form-control"
               value="{{ old('direccion', $paciente->direccion ?? '') }}">
    </div>
</div>

<hr class="my-4">

<!-- SEGURO -->
<h6 class="text-uppercase fw-bold mb-3" style="color:#7a4ea0;">
    Información adicional
</h6>

<div class="row g-3">
    <div class="col-md-6">
        <label class="form-label">ARS</label>
        <select name="seguro_medico" class="form-select">
            <option value="">--Seleccione--</option>
            <option value="ARS SeNaSa" {{ (old('seguro_medico', $paciente->seguro_medico ?? '') == 'ARS SeNaSa') ? 'selected' : '' }}>ARS SeNaSa</option>
            <option value="ARS Universal" {{ (old('seguro_medico', $paciente->seguro_medico ?? '') == 'ARS Universal') ? 'selected' : '' }}>ARS Universal</option>
            <option value="ARS Humano" {{ (old('seguro_medico', $paciente->seguro_medico ?? '') == 'ARS Humano') ? 'selected' : '' }}>ARS Humano</option>
            <option value="Mapfre Salud ARS" {{ (old('seguro_medico', $paciente->seguro_medico ?? '') == 'Mapfre Salud ARS') ? 'selected' : '' }}>Mapfre Salud ARS</option>
            <option value="Primera ARS" {{ (old('seguro_medico', $paciente->seguro_medico ?? '') == 'Primera ARS') ? 'selected' : '' }}>Primera ARS</option>
            <option value="Otro" {{ (old('seguro_medico', $paciente->seguro_medico ?? '') == 'Otro') ? 'selected' : '' }}>Otro</option>
        </select>
    </div>

    <div class="col-md-6">
        <label class="form-label">NSS</label>
        <input type="text" name="nss"
               class="form-control"
               value="{{ old('nss', $paciente->nss ?? '') }}">
    </div>
</div>
