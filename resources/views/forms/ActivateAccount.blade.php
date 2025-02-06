<div class="container mt-5">
    <h1>Formulario de Activación</h1>

    <form action="{{ route('activation.store') }}" method="POST">
        @csrf
        <div class="mb-3">
            <label for="regional" class="form-label">Regional</label>
            <input type="text" class="form-control" id="regional" name="regional" value="{{ old('regional') }}" required>
        </div>
        <div class="mb-3">
            <label for="primer_nombre" class="form-label">Primer Nombre</label>
            <input type="text" class="form-control" id="primer_nombre" name="primer_nombre" value="{{ old('primer_nombre') }}" required>
        </div>
        <div class="mb-3">
            <label for="segundo_nombre" class="form-label">Segundo Nombre</label>
            <input type="text" class="form-control" id="segundo_nombre" name="segundo_nombre" value="{{ old('segundo_nombre') }}">
        </div>
        <div class="mb-3">
            <label for="primer_apellido" class="form-label">Primer Apellido</label>
            <input type="text" class="form-control" id="primer_apellido" name="primer_apellido" value="{{ old('primer_apellido') }}" required>
        </div>
        <div class="mb-3">
            <label for="segundo_apellido" class="form-label">Segundo Apellido</label>
            <input type="text" class="form-control" id="segundo_apellido" name="segundo_apellido" value="{{ old('segundo_apellido') }}">
        </div>
        <div class="mb-3">
            <label for="correo_personal" class="form-label">Correo Personal</label>
            <input type="email" class="form-control" id="correo_personal" name="correo_personal" value="{{ old('correo_personal') }}" required>
        </div>
        <div class="mb-3">
            <label for="correo_institucional" class="form-label">Correo Institucional (@sena.edu.co)</label>
            <input type="email" class="form-control" id="correo_institucional" name="correo_institucional" value="{{ old('correo_institucional') }}" required>
        </div>
        <div class="mb-3">
            <label for="numero_contrato" class="form-label">Número de Contrato</label>
            <input type="text" class="form-control" id="numero_contrato" name="numero_contrato" value="{{ old('numero_contrato') }}" required>
        </div>
        <div class="mb-3">
            <label for="fecha_inicio_contrato" class="form-label">Fecha de Inicio del Contrato</label>
            <input type="date" class="form-control" id="fecha_inicio_contrato" name="fecha_inicio_contrato" value="{{ old('fecha_inicio_contrato') }}" required>
        </div>
        <div class="mb-3">
            <label for="fecha_terminacion_contrato" class="form-label">Fecha de Terminación del Contrato</label>
            <input type="date" class="form-control" id="fecha_terminacion_contrato" name="fecha_terminacion_contrato" value="{{ old('fecha_terminacion_contrato') }}" required>
        </div>
        <div class="mb-3">
            <label for="usuario" class="form-label">Usuario</label>
            <input type="text" class="form-control" id="usuario" name="usuario" value="{{ old('usuario') }}" required>
        </div>
        <button type="submit" class="btn btn-primary">Enviar</button>
    </form>
</div>

