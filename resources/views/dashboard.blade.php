<x-app-layout>
    

    <div class="py-12 mt-10">
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