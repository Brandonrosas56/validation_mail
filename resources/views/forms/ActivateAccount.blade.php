<!-- Modal -->
<div id="activationModal"
    class="modal-overlay fixed inset-0 flex items-center justify-center bg-gray-800 bg-opacity-50 hidden z-50">
    <div class="ModalColor rounded-lg w-full max-w-lg sm:max-w-2xl p-6 shadow-lg bg-white overflow-y-auto max-h-screen">
        <div class="flex justify-between items-center mb-4">
            <h2 class="text-lg font-bold TextColor">Formulario de Activación</h2>
            <button onclick="toggleActivationModal()" class="text-gray-500 hover:text-gray-700 cursor-pointer">
                <img src="{{ asset('img/cancel.png') }}" alt="Cerrar" class="h-6 w-6" />
            </button>
        </div>

        <form method="POST" action="{{ route('activation.store') }}" id="formActivation">
            @csrf
            <x-validation-errors class="mb-4" />
            <input type="hidden" id="operation" name="operation" value="add">

            <!-- Formulario con scroll interno -->
            <div class="grid grid-cols-1 sm:grid-cols-2 gap-4 overflow-y-auto">
                <!-- Primera columna -->
                <div>
                    <label for="regional" class="block mb-1 TextColor font-bold">Regional*</label>
                    <x-select name="rgn_id" id="rgn_id"
                        class="custom-border rounded-lg w-full p-2 bg-white focus:outline-none" required>
                        <option value="">{{ __('Select_regional') }}</option>
                        @foreach($regional as $region)
                            <option value="{{ $region->rgn_id }}">{{ $region->rgn_nombre }}</option>
                        @endforeach
                    </x-select>
                </div>

                <div>
                    <label for="rol_asignado" class="block mb-1 TextColor font-bold">Relación Contractual*</label>
                    <select name="rol_asignado" id="rol_asignado"
                        class="custom-border rounded-lg w-full p-2 bg-white focus:outline-none"
                        onclick="changeRolAssing()" required>
                        <option value="" selected disabled>Seleccione ...</option>
                        <option value="Contratista">Contratista</option>
                        <option value="Funcionario">Funcionario</option>
                    </select>
                </div>

                <div>
                    <label for="tipo_documento" class="block mb-1 TextColor font-bold">Tipo de documento*</label>
                    <select name="tipo_documento" id="tipo_documento"
                        class="custom-border rounded-lg w-full p-2 bg-white focus:outline-none">
                        <option value="" selected disabled>Selecciona el tipo de documento</option>
                        <option value="CC">Cédula de Ciudadanía</option>
                        <option value="CE">Cédula de Extranjería</option>
                        <option value="Pasaporte">Pasaporte</option>
                        <option value="TI">Tarjeta de Identidad</option>
                        <option value="NIT">Número de Identificación Tributaria</option>
                    </select>
                </div>

                <div>
                    <label for="documento_proveedor" class="block mb-1 TextColor font-bold">Documento de
                        identidad*</label>
                    <input type="text" name="documento_proveedor" id="documento_proveedor"
                        class="custom-border rounded-lg w-full p-2 bg-white focus:outline-none">
                    @if ($errors->has('documento_proveedor'))
                        <div class="text-red-500 text-sm mt-1">
                            {{ $errors->first('documento_proveedor') }}
                        </div>
                    @endif
                </div>

                <div>
                    <label for="primer_nombre" class="block mb-1 TextColor font-bold">Primer Nombre*</label>
                    <input type="text" name="primer_nombre" id="primer_nombre"
                        class="custom-border rounded-lg w-full p-2 bg-white focus:outline-none"
                        value="{{ old('primer_nombre') }}" required>

                </div>

                <div class="mb-2">
                    <label for="segundo_nombre" class="block mb-1 TextColor font-bold">Segundo Nombre</label>
                    <input type="text" name="segundo_nombre" id="segundo_nombre"
                        class="custom-border rounded-lg w-full p-2 bg-white focus:outline-none"
                        value="{{ old('segundo_nombre') }}" value="{{ old('primer_apellido') }}">
                </div>

                <div class="mb-2">
                    <label for="primer_apellido" class="block mb-1 TextColor font-bold">Primer Apellido*</label>
                    <input type="text" name="primer_apellido" id="primer_apellido"
                        class="custom-border rounded-lg w-full p-2 bg-white focus:outline-none"
                        value="{{ old('primer_apellido') }}" required>
                </div>

                <div class="mb-2">
                    <label for="segundo_apellido" class="block mb-1 TextColor font-bold">Segundo Apellido*</label>
                    <input type="text" name="segundo_apellido" id="segundo_apellido"
                        class="custom-border rounded-lg w-full p-2 bg-white focus:outline-none"
                        value="{{ old('segundo_apellido') }}">
                </div>

                <div>
                    <label for="correo_personal" class="block mb-1 TextColor font-bold">Correo Personal*</label>
                    <input type="email" name="correo_personal" id="correo_personal"
                        class="custom-border rounded-lg w-full p-2 bg-white focus:outline-none"
                        value="{{ old('correo_personal') }}" required>
                </div>

                <div>
                    <label for="correo_institucional" class="block mb-1 TextColor font-bold">Correo Institucional
                        (@sena.edu.co)*</label>
                    <input type="email" name="correo_institucional" id="correo_institucional"
                        class="custom-border rounded-lg w-full p-2 bg-white focus:outline-none"
                        value="{{ old('correo_institucional') }}" required>
                </div>

                <div>
                    <label for="fecha_inicio_contrato" class="block mb-1 TextColor font-bold">Fecha de Inicio del
                        Contrato*</label>
                    <input type="date" name="fecha_inicio_contrato" id="fecha_inicio_contrato"
                        class="custom-border rounded-lg w-full p-2 bg-white focus:outline-none" required>
                </div>

                <div id="divTermination">
                    <label for="fecha_terminacion_contrato" class="block mb-1 TextColor font-bold">Fecha de Terminación
                        del Contrato*</label>
                    <input type="date" name="fecha_terminacion_contrato" id="fecha_terminacion_contrato"
                        class="custom-border rounded-lg w-full p-2 bg-white">
                </div>

                <div>
                    <label for="numero_contrato" id="labelNum" class="block mb-1 TextColor font-bold">Número de
                        Contrato*</label>
                    <input type="text" name="numero_contrato" id="numero_contrato"
                        class="custom-border rounded-lg w-full p-2 bg-white focus:outline-none"
                        value="{{ old('numero_contrato') }}" required>
                </div>

                <div>
                    <label for="usuario" class="block mb-1 TextColor font-bold">Usuario</label>
                    <input type="text" name="usuario" id="usuario"
                        class="custom-border rounded-lg w-full p-2 bg-white focus:outline-none"
                        value="{{ old('usuario') }}" required>
                </div>
            </div>

            <!-- Botón de envío -->
            <div class="mt-6 flex justify-center w-full mb-4">
                <button type="submit" class="ButtonColor text-white font-bold py-2 px-4 rounded w-40">
                    Enviar
                </button>
            </div>
        </form>
    </div>
</div>

<script>
    // Función para alternar la visibilidad del modal
    function toggleActivationModal() {
        const modal = document.getElementById('activationModal');
        modal.classList.toggle('active'); // Alterna la clase 'active' para mostrar/ocultar el modal
    }

    function updateMinDate() {
        const fechaInicio = document.getElementById("fecha_inicio_contrato").value;
        const fechaTerminacion = document.getElementById("fecha_terminacion_contrato");
        fechaTerminacion.setAttribute("min", fechaInicio);
    }

    function changeRolAssing() {
        const selectRol = document.getElementById('rol_asignado');
        const labelNum = document.getElementById('labelNum');
        const divTermination = document.getElementById('divTermination')

        if (selectRol.value === 'Contratista') {
            labelNum.textContent = 'Número de Contrato*';
            divTermination.style.display = 'block';
        } else if (selectRol.value === 'Funcionario') {
            labelNum.textContent = 'Acta de resolución*';
            divTermination.style.display = 'none'
        }
        document.addEventListener('DOMContentLoaded', function() {
        // Capturar errores de validación de Laravel
        const errors = @json($errors->all());

        // Capturar mensaje flash de error general
        const errorMessage = "{{ session('error') }}";

        } else if (errorMessage) {
            Swal.fire({
                icon: "warning",
                title: "Aviso",
                text: errorMessage, // Mostrar mensaje flash
                confirmButtonColor: "#04324D"
            });
        }
    });
    
</script>