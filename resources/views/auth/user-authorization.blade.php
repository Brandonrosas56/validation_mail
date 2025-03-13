<x-app-layout>

<link rel="stylesheet" href="{{ asset('css/Register.css') }}">
    <div class="p-6 lg:p-8  border-gray-200">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-xl sm:rounded-lg">

                <h1 class="ms-3 mt-8 text-2xl font-medium text-gray-900 font-semibold">
                    Autorización de Acceso y Gestión de Cuentas Institucionales
                </h1>

                <p class="ms-3 mt-6 text-gray-500 leading-relaxed">
                    Al otorgar esta autorización, el usuario acepta que está facultado para autorizar a otros
                    usuarios al acceso al sistema designado, permitiéndoles realizar acciones relacionadas con
                    la solicitud, creación y activación de cuentas institucionales en su nombre.
                    El autorizador asume la responsabilidad de las acciones realizadas por los usuarios a los que
                    haya concedido acceso, de acuerdo con las políticas de seguridad de la información y las
                    normativas institucionales vigentes. Esta autorización permanecerá activa hasta que sea
                    revocada o modificada por el autorizador mediante los canales establecidos.
                    14
                    La institución se reserva el derecho de monitorear y registrar las acciones realizadas en el
                    sistema, garantizando el cumplimiento de los lineamientos establecidos y la protección de la
                    información institucional.
                </p>

                <h2 class="ms-3 text-xl font-semibold text-white  flex justify-center">                    
                    @include('forms.register')

                    <div class="color textte py-2 px-4 rounded-lg mt-4 mb-4 ">
                        <button onclick="toggleRegisterModal()" class="cms-3 text-xl font-semibold ">{{ __('registerUsers') }}</button>
                    </div>
                </h2>

                <h2 class="ms-3 text-xl font-semibold text-white  flex justify-center">
                    <div class="color textte py-2 px-4 rounded-lg mt-4 mb-4 ">
                        <a href="{{ route('show_role_functionary') }}" class="cms-3 text-xl font-semibold ">{{ __('show_role_functionary') }}</a>
                    </div>
                </h2>
            </div>
        </div>
    </div>
</x-app-layout>

<script src="https://cdn.jsdelivr.net/npm/sweetalert2@10"></script>
@if (session('success'))
<script>
    document.addEventListener('DOMContentLoaded', function() {
        Swal.fire({
            title: "¡Éxito!",
            text: "{{ session('success') }}",
            icon: "success"
        });
    });
</script>
@endif