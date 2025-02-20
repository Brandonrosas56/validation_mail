<!-- Modal -->
<div id="registerModal" class="modal-overlay fixed inset-0 bg-gray-800 bg-opacity-50 hidden" >
    <div class="ModalColor rounded-lg w-full max-w-xl sm:max-w-2xl p-6 shadow-lg">
        <div class="flex justify-between items-center mb-4">
            <h2 class="text-lg font-bold TextColor">Formulario de Registro</h2>
            <button onclick="toggleRegisterModal()" class="text-gray-500 hover:text-gray-700 cursor-pointer">
                <img src="{{ asset('img/cancel.png') }}" alt="Cerrar" class="h-6 w-6" />
            </button>
        </div>
        
        <form method="POST" action="registerStore" id="formUser">
            @csrf
            <x-validation-errors class="mb-4" />
            <input id="id" name="id" type="hidden" value="{{ $id ?? '' }}">

            <!-- Usando grid para dividir el formulario -->
            <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                <!-- Columna 1 -->
                <div class="mb-2">
                    <x-label for="name" value="{{ __('Name') }}" class="block mb-1 TextColor font-bold" />
                    <x-input id="name" class="block mt-1 w-full border-gray-300 focus:ring-blue-500 focus:border-blue-500 custom-border p-2 text-black" type="text" name="name" :value="old('name')" required autofocus />
                </div>

                <div class="mb-2">
                    <x-label for="supplier_document" value="{{ __('Supplier_document') }}" class="block mb-1 TextColor font-bold" />
                    <x-input id="supplier_document" class="block mt-1 w-full border-gray-300 focus:ring-blue-500 focus:border-blue-500 custom-border p-2 text-black" type="text" name="supplier_document" :value="old('supplier_document')" required />
                </div>

                <div class="mb-2">
                    <x-label for="email" value="{{ __('Email') }}" class="block mb-1 TextColor font-bold" />
                    <x-input id="email" class="block mt-1 w-full border-gray-300 focus:ring-blue-500 focus:border-blue-500 custom-border p-2 text-black" type="email" name="email" :value="old('email')" required />
                </div>

                <div class="mb-2">
                    <x-label for="password" value="{{ __('Password') }}" class="block mb-1 TextColor font-bold" />
                    <x-input id="password" class="block mt-1 w-full border-gray-300 focus:ring-blue-500 focus:border-blue-500 custom-border p-2 text-black" type="password" name="password" required />
                </div>

                <div class="mb-2">
                    <x-label for="password_confirmation" value="{{ __('Confirm Password') }}" class="block mb-1 TextColor font-bold" />
                    <x-input id="password_confirmation" class="block mt-1 w-full border-gray-300 focus:ring-blue-500 focus:border-blue-500 custom-border p-2 text-black" type="password" name="password_confirmation" required />
                </div>

                <div class="mb-2">
                    <x-label for="rgn_id" value="{{ __('Select_regional') }}" class="block mb-1 TextColor font-bold" />
                    <x-select name="rgn_id" id="rgn_id" class="custom-border rounded-lg w-full p-2 bg-white focus:outline-none text-black">
                        <option value="">{{__('Select_regional')}}</option>
                        @foreach($regional as $region)
                            <option value="{{ $region->rgn_id }}">{{ $region->rgn_nombre }}</option>
                        @endforeach
                    </x-select>
                </div>
            </div>

            <input id="password_confirmation" class="block mt-1 w-full" type="hidden" name="rol" value="Asistente" />
            
            <!-- Botones -->
            <div class="mt-6 flex justify-between w-full mb-4">
                <x-button class="ButtonColor text-white font-bold py-2 px-4 rounded w-40">
                    {{ __('Register') }}
                </x-button>
                <x-button type="button" onclick="clearForm()" class="bg-red-500 hover:bg-red-600 py-2 px-4 rounded w-40">
                    {{__('Clear')}}
                </x-button>
            </div>
        </form>
    </div>
</div>

<script>
    function toggleRegisterModal() {
        document.getElementById('registerModal').classList.toggle('active');
    }

    function clearForm() {
        document.getElementById('formUser').reset();
    }
</script>
