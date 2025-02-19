<div class="modal-overlay fixed inset-0 bg-gray-800 bg-opacity-50 hidden" id="registerModal">
    <div class="ModalColor rounded-lg w-full max-w-xl sm:max-w-2xl p-6 shadow-lg">
        <div class="flex justify-between items-center mb-4">
            <h2 class="text-lg font-bold TextColor">Formulario de Registro</h2>
            <button onclick="toggleRegisterModal()" class="text-gray-500 hover:text-gray-700 cursor-pointer">
                <img src="{{ asset('img/cancel.png') }}" alt="Cerrar" class="h-6 w-6" />
            </button>
        </div>
        <script>
            document.addEventListener('DOMContentLoaded', function() {
                editUser('', '', '', '');
            })

            //!clean the form
            function clearForm() {
                document.getElementById('formUser').reset();
            }
        </script>

        <div class="flex items-center justify-center min-h-screen">
            <div class="w-96 max-w-md">

                <div class="flex justify-center mb-2">
                    <img src="{{ asset('img/logo_sena_web.svg')}}" alt="Imagen arriba" class="w-20 h-20">
                </div>

                <!-- Form  roles -->
                <div>
                    <form method="POST" action="registerStore" id="formUser">
                        @csrf
                        <x-validation-errors class="mb-4" />
                        <div>
                            <x-input id="id" name="id" type="hidden" :value="$id ?? ''"></x-input>
                        </div>
                        <div>
                            <x-label for="name" value="{{ __('Name') }}" class="mt-8" />
                            <x-input id="name" class="block mt-1 w-full" type="text" name="name" :value="old('name')" required autofocus autocomplete="name" />
                        </div>

                        <div>
                            <x-label for="supplier_document" value="{{ __('Supplier_document') }}" class="mt-8" />
                            <x-input id="supplier_document" class="block mt-1 w-full" type="text" name="supplier_document" :value="old('supplier_document')" required />
                        </div>

                        <div class="mt-4">
                            <x-label for="email" value="{{ __('Email') }}" />
                            <x-input id="email" class="block mt-1 w-full" type="email" name="email" :value="old('email')" required autocomplete="username" />
                        </div>

                        <div class="mt-4">
                            <x-label for="password" value="{{ __('Password') }}" />
                            <x-input id="password" class="block mt-1 w-full" type="password" name="password" autocomplete="new-password" />
                        </div>

                        <div class="mt-4">
                            <x-label for="password_confirmation" value="{{ __('Confirm Password') }}" />
                            <x-input id="password_confirmation" class="block mt-1 w-full" type="password" name="password_confirmation" autocomplete="new-password" />
                        </div>

                        <div class="mt-4">
                            <x-label for="Select_regional" value="{{ __('Select_regional') }}" />
                            <x-select name="rgn_id" id="rgn_id" class="block mt-1 w-full">
                                <option value="">{{__('Select_regional')}}</option>
                                @foreach($regional as $region)
                                <option value="{{ $region->rgn_id }}">{{ $region->rgn_nombre }}</option>
                                @endforeach
                            </x-select>
                        </div>

                        <input id="password_confirmation" class="block mt-1 w-full" type="hidden" name="rol" value="Asistente" />

                        <div class="flex justify-between w-full mb-4">
                            <x-button class="mt-4">
                                {{ __('Register') }}
                            </x-button>
                            <x-onclick class="mt-4" onclick="clearForm()">
                                {{__('Clear')}}
                            </x-onclick>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Modal toggle logic -->
<script>
    function toggleRegisterModal() {
        const modal = document.getElementById('registerModal');
        modal.classList.toggle('hidden');
    }
</script>