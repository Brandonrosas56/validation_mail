<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Dashboard') }}
        </h2>
    </x-slot>

    <div class="py-12">
        @if (session('mensaje'))
        <script>
            document.addEventListener("DOMContentLoaded", function() {
                alertPermission();
            });
        </script>
        @endif
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-xl sm:rounded-lg">
                <x-welcome />
            </div>
        </div>
    </div>
</x-app-layout>

<script>
    function alertPermission() {
        Swal.fire({
            icon: "warning",
            title: "Permiso denegado",
            text: "Tú usuario no tiene permiso para haceder a esa opción, por favor comuniquese con el administrador",
            button: 'OK'
        })
    }
</script>