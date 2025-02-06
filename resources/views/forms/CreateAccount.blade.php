<!-- Modal -->
<head>
<link rel="stylesheet" href="{{ asset('css/CreateAccount.css') }}">
</head>
<div class="modal fade" id="createAccountModal" tabindex="-1" aria-labelledby="createAccountModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="createAccountModalLabel">Crear Solicitud</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <form action="{{ route('create-account.store') }}" method="POST">
                    @csrf
                    <div class="mb-3">
                        <label for="regional" class="form-label">Regional</label>
                        <input type="text" name="regional" id="regional" class="form-control" value="{{ old('regional') }}" required>
                    </div>
                    <div class="mb-3">
                        <label for="primer_nombre" class="form-label">Primer Nombre</label>
                        <input type="text" name="primer_nombre" id="primer_nombre" class="form-control" value="{{ old('primer_nombre') }}" required>
                    </div>
                    <div class="mb-3">
                        <label for="segundo_nombre" class="form-label">Segundo Nombre</label>
                        <input type="text" name="segundo_nombre" id="segundo_nombre" class="form-control" value="{{ old('segundo_nombre') }}">
                    </div>
                    <div class="mb-3">
                        <label for="primer_apellido" class="form-label">Primer Apellido</label>
                        <input type="text" name="primer_apellido" id="primer_apellido" class="form-control" value="{{ old('primer_apellido') }}" required>
                    </div>
                    <div class="mb-3">
                        <label for="segundo_apellido" class="form-label">Segundo Apellido</label>
                        <input type="text" name="segundo_apellido" id="segundo_apellido" class="form-control" value="{{ old('segundo_apellido') }}">
                    </div>
                    <div class="mb-3">
                        <label for="correo_personal" class="form-label">Correo Personal</label>
                        <input type="email" name="correo_personal" id="correo_personal" class="form-control" value="{{ old('correo_personal') }}" required>
                    </div>
                    <div class="mb-3">
                        <label for="numero_contrato" class="form-label">Número de Contrato</label>
                        <input type="text" name="numero_contrato" id="numero_contrato" class="form-control" value="{{ old('numero_contrato') }}" required>
                    </div>
                    <div class="mb-3">
                        <label for="fecha_inicio_contrato" class="form-label">Fecha de Inicio del Contrato</label>
                        <input type="date" name="fecha_inicio_contrato" id="fecha_inicio_contrato" class="form-control" value="{{ old('fecha_inicio_contrato') }}" required>
                    </div>
                    <div class="mb-3">
                        <label for="fecha_terminacion_contrato" class="form-label">Fecha de Terminación del Contrato</label>
                        <input type="date" name="fecha_terminacion_contrato" id="fecha_terminacion_contrato" class="form-control" value="{{ old('fecha_terminacion_contrato') }}" required>
                    </div>
                    <button type="submit" class="btn btn-primary">Enviar</button>
                </form>
            </div>
        </div>
    </div>
</div>
