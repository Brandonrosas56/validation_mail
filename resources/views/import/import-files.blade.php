<x-app-layout>
    <!-- SweetAlert2 -->
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@10"></script>

    <!-- Mostrar errores con SweetAlert2 -->
    @if ($errors->has('error'))
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const errorMessage = "{{ $errors->first('error') }}";
            if (errorMessage) {
                Swal.fire({
                    icon: "warning",
                    title: "Aviso",
                    text: errorMessage,
                    confirmButtonColor: "#04324D"
                });
            }
        });
    </script>
    @endif

    <!-- Mostrar éxito con SweetAlert2 -->
    @if (session('success'))
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            Swal.fire({
                title: "Éxito!",
                text: "{{ session('success') }}",
                icon: "success",
                confirmButtonColor: "#04324D"
            });
        });
    </script>
    @endif

    <!-- Formulario de carga de archivos -->
    <div class="max-w-7xl mx-auto mt-10 p-6 bg-white rounded-lg shadow-md">
        <form action="{{ route('import-files') }}" method="post" enctype="multipart/form-data" id="formUploadFile" class="space-y-6">
            @csrf

            <!-- Campo para cargar archivo -->
            <div>
                <label for="upload_file" class="block text-sm font-medium text-gray-700">Cargar archivo con formato CSV</label>
                <input type="file" name="upload_file" id="upload_file" class="mt-1 block w-full px-4 py-2 border border-gray-300 rounded-lg focus:outline-none focus:border-blue-400 focus:ring-2 focus:ring-blue-200 transition duration-150 ease-in-out">
            </div>

            <!-- Selección del tipo de archivo -->
            <div>
                <label for="typeFile" class="block text-sm font-medium text-gray-700">Tipo de archivo</label>
                <select name="typeFile" id="typeFile" class="mt-1 block w-full px-4 py-2 border border-gray-300 rounded-lg focus:outline-none focus:border-blue-400 focus:ring-2 focus:ring-blue-200 transition duration-150 ease-in-out">
                    <option value="" selected disabled>Selecciona el tipo de archivo</option>
                    <option value="Regional">Regionales</option>
                    <option value="Administrators">Administradores</option>
                </select>
            </div>

            <!-- Espacio adicional antes del botón -->
            <div class="mt-8"> <!-- Aumenté el margen superior aquí -->
                <button type="submit" class="w-full flex justify-center px-6 py-2 text-lg font-semibold text-white rounded-lg transition duration-150 ease-in-out" style="background-color: #04324D;">
                    Subir archivo
                </button>
            </div>
        </form>
    </div>

    <!-- Validaciones del formulario -->
    <script>
        document.getElementById("formUploadFile").addEventListener("submit", function(event) {
            const typeFile = document.getElementById("typeFile").value;
            const uploadFile = document.getElementById("upload_file").files.length;

            if (!typeFile) {
                event.preventDefault();
                Swal.fire({
                    icon: "warning",
                    title: "Falta seleccionar el tipo de archivo",
                    text: "Por favor, selecciona el tipo de archivo antes de continuar.",
                    confirmButtonColor: "#04324D"
                });
                return;
            }

            if (!uploadFile) {
                event.preventDefault();
                Swal.fire({
                    icon: "warning",
                    title: "Falta seleccionar un archivo",
                    text: "Por favor, selecciona un archivo antes de subirlo.",
                    confirmButtonColor: "#04324D"
                });
            }
        });
    </script>
</x-app-layout>