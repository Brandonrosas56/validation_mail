<!-- Modal -->
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
            <x-validation-errors class="mb-4" />
            <input type="hidden" id="operation" name="operation" value="add">
            
            <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                <div class="mb-3">
                    <label for="regional" class="block mb-1 TextColor font-bold">Regional</label>
                    <x-select name="rgn_id" id="rgn_id" class="custom-border rounded-lg w-full p-2 bg-white focus:outline-none" required>
                        <option value="">{{ __('Select_regional') }}</option>
                        @foreach($regional as $region)
                        <option value="{{ $region->rgn_id }}">{{ $region->rgn_nombre }}</option>
                        @endforeach
                    </x-select>
                </div>

                <div class="mb-3">
                    <label for="primer_nombre" class="block mb-1 TextColor font-bold">Primer Nombre</label>
                    <input type="text" name="primer_nombre" id="primer_nombre" class="custom-border rounded-lg w-full p-2 bg-white focus:outline-none" value="{{ old('primer_nombre') }}" required>
                </div>

                <div class="mb-3">
                    <label for="segundo_nombre" class="block mb-1 TextColor font-bold">Segundo Nombre</label>
                    <input type="text" name="segundo_nombre" id="segundo_nombre" class="custom-border rounded-lg w-full p-2 bg-white focus:outline-none" value="{{ old('segundo_nombre') }}">
                </div>

                <div class="mb-3">
                    <label for="primer_apellido" class="block mb-1 TextColor font-bold">Primer Apellido</label>
                    <input type="text" name="primer_apellido" id="primer_apellido" class="custom-border rounded-lg w-full p-2 bg-white focus:outline-none" value="{{ old('primer_apellido') }}" required>
                </div>

                <div class="mb-3">
                    <label for="segundo_apellido" class="block mb-1 TextColor font-bold">Segundo Apellido</label>
                    <input type="text" name="segundo_apellido" id="segundo_apellido" class="custom-border rounded-lg w-full p-2 bg-white focus:outline-none" value="{{ old('segundo_apellido') }}">
                </div>

                <div class="mb-3">
                    <label for="documento_proveedor" class="block mb-1 TextColor font-bold">Documento Proveedor</label>
                    <input type="text" name="documento_proveedor" id="documento_proveedor" class="custom-border rounded-lg w-full p-2 bg-white focus:outline-none" value="{{ old('documento_proveedor') }}" required>
                </div>

                <div class="mb-3">
                    <label for="correo_personal" class="block mb-1 TextColor font-bold">Correo Personal</label>
                    <input type="email" name="correo_personal" id="correo_personal" class="custom-border rounded-lg w-full p-2 bg-white focus:outline-none" value="{{ old('correo_personal') }}" required>
                </div>

                <div class="mb-3">
                    <label for="numero_contrato" class="block mb-1 TextColor font-bold">Número de Contrato</label>
                    <input type="text" name="numero_contrato" id="numero_contrato" class="custom-border rounded-lg w-full p-2 bg-white focus:outline-none" value="{{ old('numero_contrato') }}" required>
                </div>

                <div class="mb-3">
                    <label for="fecha_inicio_contrato" class="block mb-1 TextColor font-bold">Fecha de Inicio del Contrato</label>
                    <input type="date" name="fecha_inicio_contrato" id="fecha_inicio_contrato" class="custom-border rounded-lg w-full p-2 bg-white focus:outline-none" value="{{ old('fecha_inicio_contrato') }}" required onchange="updateMinDate()">
                </div>

                <div class="mb-3">
                    <label for="fecha_terminacion_contrato" class="block mb-1 TextColor font-bold">Fecha de Terminación del Contrato</label>
                    <input type="date" name="fecha_terminacion_contrato" id="fecha_terminacion_contrato" class="custom-border rounded-lg w-full p-2 bg-white focus:outline-none" value="{{ old('fecha_terminacion_contrato') }}" required>
                </div>

                <div class="mb-3">
                    <label for="user_id" class="block mb-1 TextColor font-bold">Rol Asignado </label>
                    <input type="text" name="user_id" id="user_id" class="custom-border rounded-lg w-full p-2 bg-white focus:outline-none" value="{{ old('fecha_terminacion_contrato') }}" required>
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

<!-- Modal toggle logic -->
<script>
    function toggleModal() {
        const modal = document.getElementById('userModal');
        modal.classList.toggle('active');  
    }

    function updateMinDate() {
        const fechaInicio = document.getElementById("fecha_inicio_contrato").value;
        const fechaTerminacion = document.getElementById("fecha_terminacion_contrato");
        fechaTerminacion.setAttribute("min", fechaInicio);
    }
</script>
