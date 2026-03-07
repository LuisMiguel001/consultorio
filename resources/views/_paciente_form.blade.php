<input type="hidden" name="paciente_id" value="{{ $paciente->id ?? '' }}">
<!-- DATOS PERSONALES -->
<h6 class="text-uppercase fw-bold mb-3" style="color:#7a4ea0;">
    Datos personales
</h6>

<div class="row g-3">
    <div class="col-md-6">
        <label class="form-label">Nombre</label> <span class="text-danger">*</span>
        <input type="text" name="nombre" class="form-control @error('nombre') is-invalid @enderror"
            value="{{ old('nombre', $paciente->nombre ?? '') }}" required>
        @error('nombre')
            <div class="invalid-feedback d-block">
                {{ $message }}
            </div>
        @enderror
    </div>

    <div class="col-md-6">
        <label class="form-label">Apellido</label> <span class="text-danger">*</span>
        <input type="text" name="apellido" class="form-control @error('apellido') is-invalid @enderror"
            value="{{ old('apellido', $paciente->apellido ?? '') }}" required>
        @error('apellido')
            <div class="invalid-feedback">{{ $message }}</div>
        @enderror
    </div>

    <div class="col-md-6">
        <label class="form-label">Cédula</label>

        <input type="text" id="cedula" name="cedula" minlength="13" maxlength="13"
            class="form-control @error('cedula') is-invalid @enderror"
            value="{{ old('cedula', $paciente->cedula ?? '') }}">

        <div class="form-check mt-2">
            <input class="form-check-input" type="checkbox" id="extranjero">
            <label class="form-check-label">
                Paciente extranjero (sin formato dominicano)
            </label>
        </div>

        @error('cedula')
            <div class="invalid-feedback">{{ $message }}</div>
        @enderror
    </div>

    <div class="col-md-6">
        <label class="form-label">Fecha de nacimiento</label> <span class="text-danger">*</span>
        <input type="date" name="fecha_nacimiento"
            class="form-control @error('fecha_nacimiento') is-invalid @enderror"
            value="{{ old('fecha_nacimiento', $paciente->fecha_nacimiento ?? '') }}" required>
        @error('fecha_nacimiento')
            <div class="invalid-feedback">{{ $message }}</div>
        @enderror
    </div>

    <div class="col-md-6">
        <label class="form-label">Sexo</label> <span class="text-danger">*</span>
        <select name="sexo" class="form-select @error('sexo') is-invalid @enderror" required>
            <option value="">--Seleccione--</option>
            <option value="Masculino" {{ old('sexo', $paciente->sexo ?? '') == 'Masculino' ? 'selected' : '' }}>
                Masculino</option>
            <option value="Femenino" {{ old('sexo', $paciente->sexo ?? '') == 'Femenino' ? 'selected' : '' }}>
                Femenino</option>
            <option value="Otro" {{ old('sexo', $paciente->sexo ?? '') == 'Otro' ? 'selected' : '' }}>Otro
            </option>
        </select>
        @error('sexo')
            <div class="invalid-feedback">{{ $message }}</div>
        @enderror
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

        <input type="text" id="telefono" name="telefono" maxlength="12" class="form-control"
            value="{{ old('telefono', $paciente->telefono ?? '') }}" placeholder="809-000-0000">

        <div class="form-check mt-2">
            <input class="form-check-input" type="checkbox" id="telefono_extranjero">
            <label class="form-check-label">
                Teléfono extranjero
            </label>
        </div>

    </div>
    <div class="col-md-6">
        <label class="form-label">Correo electrónico</label>
        <input type="email" name="email" class="form-control" value="{{ old('email', $paciente->email ?? '') }}">
    </div>

    <div class="col-md-12">
        <label class="form-label">Dirección</label>
        <input type="text" name="direccion" class="form-control"
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
        <select name="seguro_medico" id="seguro_medico" class="form-select">
            <option value="">--Seleccione--</option>
            <option value="ARS SeNaSa"
                {{ old('seguro_medico', $paciente->seguro_medico ?? '') == 'ARS SeNaSa' ? 'selected' : '' }}>ARS
                SeNaSa</option>
            <option value="ARS Universal"
                {{ old('seguro_medico', $paciente->seguro_medico ?? '') == 'ARS Universal' ? 'selected' : '' }}>ARS
                Universal</option>
            <option value="ARS Humano"
                {{ old('seguro_medico', $paciente->seguro_medico ?? '') == 'ARS Humano' ? 'selected' : '' }}>ARS
                Humano</option>
            <option value="Mapfre Salud ARS"
                {{ old('seguro_medico', $paciente->seguro_medico ?? '') == 'Mapfre Salud ARS' ? 'selected' : '' }}>
                Mapfre Salud ARS</option>
            <option value="Primera ARS"
                {{ old('seguro_medico', $paciente->seguro_medico ?? '') == 'Primera ARS' ? 'selected' : '' }}>
                Primera
                ARS</option>
            <option value="Otro"
                {{ old('seguro_medico', $paciente->seguro_medico ?? '') == 'Otro' ? 'selected' : '' }}>Otro
            </option>
        </select>
    </div>

    <div class="col-md-6">
        <label class="form-label">NSS</label>
        <input type="text" name="nss" id="nss" class="form-control" maxlength="11"
            inputmode="numeric" pattern="[0-9]{11}" value="{{ old('nss', $paciente->nss ?? '') }}" disabled>
    </div>
</div>

<script>
    document.addEventListener("DOMContentLoaded", function() {

        const ars = document.getElementById('seguro_medico');
        const nss = document.getElementById('nss');
        const telefono = document.getElementById("telefono");
        const telefonoExtranjero = document.getElementById("telefono_extranjero");

        const cedula = document.getElementById("cedula");
        const extranjero = document.getElementById("extranjero");

        function validarSeguro() {

            if (ars.value !== "") {
                nss.disabled = false;
            } else {
                nss.disabled = true;
                nss.value = "";
            }

        }

        ars.addEventListener("change", validarSeguro);

        validarSeguro();

        /* TELEFONO DOMINICANO */
        telefono.addEventListener("input", function() {

            if (telefonoExtranjero.checked) return;

            let value = telefono.value.replace(/\D/g, '');

            if (value.length > 3 && value.length <= 6) {
                value = value.replace(/(\d{3})(\d+)/, "$1-$2");
            } else if (value.length > 6) {
                value = value.replace(/(\d{3})(\d{3})(\d+)/, "$1-$2-$3");
            }

            telefono.value = value;

        });


        /* TELEFONO EXTRANJERO */
        telefonoExtranjero.addEventListener("change", function() {

            if (this.checked) {

                telefono.value = "";
                telefono.placeholder = "+1 000 000 0000";
                telefono.removeAttribute("maxlength");

            } else {

                telefono.value = "";
                telefono.placeholder = "809-000-0000";
                telefono.setAttribute("maxlength", "12");

            }

        });

        /* CEDULA DOMINICANA */
        cedula.addEventListener("input", function() {

            if (extranjero.checked) return;

            let value = cedula.value.replace(/\D/g, '');

            if (value.length > 3 && value.length <= 10) {
                value = value.replace(/(\d{3})(\d+)/, "$1-$2");
            } else if (value.length > 10) {
                value = value.replace(/(\d{3})(\d{7})(\d+)/, "$1-$2-$3");
            }

            cedula.value = value;

        });


        function actualizarCedula() {

            if (extranjero.checked) {

                cedula.placeholder = "Pasaporte o identificación";
                cedula.removeAttribute("maxlength");

            } else {

                cedula.placeholder = "000-0000000-0";
                cedula.setAttribute("maxlength", "13");

            }

        }

        extranjero.addEventListener("change", actualizarCedula);

        actualizarCedula(); // ← esto lo ejecuta al cargar la página
    });
</script>
