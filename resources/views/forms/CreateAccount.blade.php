<div class="modal-overlay fixed inset-0 bg-gray-800 bg-opacity-50 hidden" id="userModal">
    <div class="ModalColor rounded-lg w-full max-w-2xl p-6 shadow-lg max-h-[75vh] overflow-y-auto">
        <div class="flex justify-between items-center mb-4">
            <h2 class="text-lg font-bold TextColor">Crear Solicitud</h2>
            <button onclick="toggleModal()" class="text-gray-500 hover:text-gray-700 cursor-pointer">
                <img src="{{ asset('img/cancel.png') }}" alt="Cerrar" class="h-6 w-6" />
            </button>
        </div>
        <form method="POST" action="{{ route('create-account.store') }}" id="formUser">
            @csrf

            <input type="hidden" id="operation" name="operation" value="add"></input>

            <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                <div class="mb-2">
                    <label for="regional" class="block mb-1 TextColor font-bold">Regional*</label>
                    <x-select name="rgn_id" id="rgn_id" class="custom-border rounded-lg w-full p-2 bg-white focus:outline-none" required>
                        <option value="">{{ __('Select_regional') }}</option>
                        @foreach($regional as $region)
                        <option value="{{ $region->rgn_id }}">{{ $region->rgn_nombre }}</option>
                        @endforeach
                    </x-select>
                </div>

                <div class="mb-2">
                    <label for="rol_asignado" class="block mb-1 TextColor font-bold">Relación Contractual*</label>
                    <select name="rol_asignado" id="rol_asignado" class="custom-border rounded-lg w-full p-2 bg-white focus:outline-none" onclick="changeRolAssing()" required>
                    <option value="" selected disabled>Seleccione ...</option>
                        <option value="Contratista">Contratista</option>
                        <option value="Funcionario">Funcionario</option>
                    </select>
                </div>

                <div class="mb-3">
                    <label for="tipo_documento" class="block mb-1 TextColor font-bold">Tipo de documento*</label>
                    <select name="tipo_documento" id="tipo_documento" class="custom-border rounded-lg w-full p-2 bg-white focus:outline-none">
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
            
                <div class="mb-2">
                    <label for="primer_nombre" class="block mb-1 TextColor font-bold">Primer Nombre*</label>
                    <input type="text" name="primer_nombre" id="primer_nombre" class="custom-border rounded-lg w-full p-2 bg-white focus:outline-none" value="{{ old('primer_nombre') }}" required>
                </div>

                <div class="mb-2">
                    <label for="segundo_nombre" class="block mb-1 TextColor font-bold">Segundo Nombre</label>
                    <input type="text" name="segundo_nombre" id="segundo_nombre" class="custom-border rounded-lg w-full p-2 bg-white focus:outline-none" value="{{ old('segundo_nombre') }}">
                </div>

                <div class="mb-2">
                    <label for="primer_apellido" class="block mb-1 TextColor font-bold">Primer Apellido*</label>
                    <input type="text" name="primer_apellido" id="primer_apellido" class="custom-border rounded-lg w-full p-2 bg-white focus:outline-none" value="{{ old('primer_apellido') }}" required>
                </div>

                <div class="mb-2">
                    <label for="segundo_apellido" class="block mb-1 TextColor font-bold">Segundo Apellido*</label>
                    <input type="text" name="segundo_apellido" id="segundo_apellido" class="custom-border rounded-lg w-full p-2 bg-white focus:outline-none" value="{{ old('segundo_apellido') }}">
                </div>

                <div class="mb-2">
                    <label for="correo_personal" class="block mb-1 TextColor font-bold">Correo Personal*</label>
                    <input type="email" name="correo_personal" id="correo_personal" class="custom-border rounded-lg w-full p-2 bg-white focus:outline-none" value="{{ old('correo_personal') }}" required>
                </div>

                <div class="mb-2">
                    <label for="fecha_inicio_contrato" class="block mb-1 TextColor font-bold">Fecha de Inicio del Contrato*</label>
                    <input type="date" name="fecha_inicio_contrato" id="fecha_inicio_contrato" class="custom-border rounded-lg w-full p-2 bg-white focus:outline-none" value="{{ old('fecha_inicio_contrato') }}" required onchange="updateMinDate()">
                </div>

                <div class="mb-2" id="divTermination">
                    <label for="fecha_terminacion_contrato" class="block mb-1 TextColor font-bold">Fecha de Terminación del Contrato*</label>
                    <input type="date" name="fecha_terminacion_contrato" id="fecha_terminacion_contrato" class="custom-border rounded-lg w-full p-2 bg-white" value="{{ old('fecha_terminacion_contrato') }}">
                </div>

                <div class="mb-2">
                    <label for="numero_contrato" id="labelNum" class="block mb-1 TextColor font-bold">Número de Contrato*</label>
                    <input type="text" name="numero_contrato" id="numero_contrato" class="custom-border rounded-lg w-full p-2 bg-white focus:outline-none" value="{{ old('numero_contrato') }}" required>
                </div>
            </div>

            <div class="mt-4 flex justify-center w-full mb-3">
                <button type="submit" class="ButtonColor text-white font-bold py-2 px-4 rounded w-40">
                    Enviar
                </button>
            </div>
        </form>
    </div>
</div>

<script>
    function toggleModal() {
        const modal = document.getElementById('userModal');
        modal.classList.toggle('active');
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
    }

    document.addEventListener('DOMContentLoaded', function() {
        const errors = @json($errors->all());
        const errorMessage = "{{ session('error') }}";

        if (errors.length > 0) {
            Swal.fire({
                icon: "warning",
                title: "Aviso",
                html: errors.map(error => `• ${error}`).join("<br>"), 
                confirmButtonColor: "#04324D"
            });
        } else if (errorMessage) {
            Swal.fire({
                icon: "warning",
                title: "Aviso",
                text: errorMessage, 
                confirmButtonColor: "#04324D"
            });
        }
    });
</script>