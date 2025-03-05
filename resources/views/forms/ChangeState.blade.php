@php
    use App\Models\CreateAccount;
@endphp

<!-- Modal -->
<div class="modal-overlay fixed inset-0 bg-gray-800 bg-opacity-50 hidden" id="stateModal">
    <div class="ModalColor rounded-lg w-full max-w-2xl p-6 shadow-lg max-h-[75vh] overflow-y-auto">
        <div class="flex justify-between items-center mb-4">
            <h2 class="text-lg font-bold TextColor">Cambiar estados</h2>
            <button onclick="toggleModalState()" class="text-gray-500 hover:text-gray-700 cursor-pointer">
                <img src="{{ asset('img/cancel.png') }}" alt="Cerrar" class="h-6 w-6" />
            </button>
        </div>
        <form method="POST" action="{{ route('change.store') }}" id="formState">
            @csrf
            <x-validation-errors class="mb-4" />
            <input type="hidden" id="operation" name="operation" value="add">

            <div class="grid grid-cols-1 md:grid-cols-1 gap-4 text-center">
                <div class="mb-3">
                    <label for="state" class="block mb-1 TextColor font-bold">Estado*</label>
                    <x-select name="state" id="state"
                        class="custom-border rounded-lg w-full p-2 bg-white focus:outline-none" required>
                        <option value="">{{ __('Select_state') }}</option>
                        @foreach(CreateAccount::MANUAL_STATES as $state)
                            <option value="{{ $state }}" style="text-transform: capitalize;">{{ $state }}</option>
                        @endforeach
                    </x-select>
                </div>
                <div class="mb-3">
                    <input type="hidden" name="account_id" id="account_id" class="hidden" value="" required>
                    <input type="hidden" name="type" id="type" class="hidden" value="" required>
                </div>

            </div>
            <div class="mt-4 flex justify-center w-full mb-3">
                <button type="button" class="ButtonColor text-white font-bold py-2 px-4 rounded w-40"
                    id="change_button">
                    Cambiar
                </button>
            </div>
        </form>
    </div>
</div>

<!-- Modal toggle logic -->
<script>
    function toggleModalState(id,type) {
        const modal = document.getElementById('stateModal');
        modal.classList.toggle('active');
        if (id) {
            const field_id = document.getElementById('account_id');
            const field_type = document.getElementById('type');
            field_id.value = id;
            field_type.value = type;
        }
    }
    

    const changeButton = document.getElementById('change_button');
    const select = document.getElementById('state');

    changeButton.addEventListener('click', function (event) {
        event.preventDefault();

        Swal.fire({
            title: `¿Está seguro de que desea cambiar el estado a <strong>${select.options[select.selectedIndex].text}</strong>?`,
            showCancelButton: true,
            confirmButtonText: "Cambiar",
            confirmButtonColor: "#00324d",
            cancelButtonColor: "#e1002d",
            cancelButtonText: "Cancelar"
        }).then((result) => {
            if (result.isConfirmed) {
                console.log('Cambio confirmado');
                document.getElementById('formState').submit();
            }
        });
    });
</script>