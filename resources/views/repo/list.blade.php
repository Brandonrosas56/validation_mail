<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Ruta') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="flex flex-col bg-white  gap-4 overflow-hidden shadow-xl sm:rounded-lg p-6">
                <h2 class="px-2 py-1 bg-tertiary text-primary rounded-lg"><i class="fa-solid fa-folder-tree"></i> Ruta
                    Actual: {{ $directory }}</h2>
                <hr>
                <h2 class="text-lg font-semibold mb-2">Archivos:</h2>
                <ul class="mb-4 flex flex-col gap-2">
                    @foreach ($files as $file)
                        @if (substr($file, 0, 1) !== '.')
                            <div
                                class="flex flex-row items-center px-6 rounded-lg hover:bg-alternate-background-100 transition-all hover-change-button">
                                <i class="fa-solid fa-file text-alternate-background"></i>
                                <div class="text-alternate-background flex-1 w-64 p-4 ">
                                    {{ $file }}
                                    @if (substr($file, strlen($file) - 4) == '.zip')
                                        <form action="{{ Route('unzip') }}" method="post">
                                            @csrf
                                            <input type="hidden" name="directory" value="{{ $directory }}">
                                            <input type="hidden" name="file" value="{{ $file }}"
                                                id="">
                                            <button
                                                class="unzipButton hidden px-4 rounded-lg py-2 bg-gray-200 change-button transition-all"
                                                type="submit" id="unzipButton">Descomprimir</button>
                                        </form>
                                    @endif

                        </div>
                        <form id="formSelectFile" action="{{route('select_file')}}" method="POST">
                            @csrf
                            <input type="hidden" name="currentPath" value="{{ $file }}"/>
                            <button class="hidden px-4 rounded-lg py-2 bg-gray-200 change-button transition-all" type="submit">
                                Mover archivo
                            </button>
                        </form>
                        <button class="hidden px-4 rounded-lg py-2 bg-gray-200 change-button transition-all" onclick="copyToClipboard('{{ $file }}', '{{ $basePath}}')">
                            Copiar Enlace
                        </button>

                            </div>
                        @endif
                    @endforeach
                </ul>

                <h2 class="text-lg font-semibold mb-2">Directorios:</h2>
                <ul class="mb-4">
                    @foreach ($directories as $dir)
                        <li
                            class="flex flex-row items-center px-6 rounded-lg hover:bg-alternate-background-100 transition-all hover-change-button">
                            <i class="fa-regular fa-folder-open text-alternate-background"></i>
                            <a href="{{ url('/list?directory=' . $dir) }}"
                                class="text-alternate-background flex-1 w-64 p-4 ">{{ basename($dir) }}</a>
                            <a href="{{ url('/list?directory=' . $dir) }}"
                                class="hidden px-4 rounded-lg py-2 bg-gray-200 change-button transition-all">
                                Ir
                            </a>
                        </li>
                    @endforeach
                </ul>


                <div class="flex flex-col gap-4 sm:flex-row items-center  sm:justify-around px-4 py-10">
                    @if ($basePath !== '/list' && strlen($directory) > 2)
                        <div>
                            <a href="{{ url('/list?directory=' . $basePath) }}"
                                class="border-2 border-primary text-primary font-bold rounded-lg px-4 py-2 hover:bg-primary hover:text-color-info transition-all line-clamp-1">Regresar
                                un nivel</a>
                        </div>
                    @endif
                    <div>
                        <a href="{{ url('/list') }}"
                            class="border-2 border-color-error bg-color-error-100 text-color-error font-bold rounded-lg px-4 py-2 hover:bg-color-error hover:text-color-info transition-all line-clamp-1">Volver
                            al directorio raíz</a>
                    </div>

                </div>
                <hr>

                @if (Auth::user()->role != '3')
                    <h1 class="text-center font-bold py-4 "> ACCIONES</h1>
                    <div class="grid grid-cols-1 sm:grid-cols-2 gap-2 ">
                        <div class="w-full">
                            <div class="flex flex-col bg-white overflow-hidden rounded-lg p-6 gap-3">
                                <div>
                                    <h3 class="text-xl font-semibold">Subir a: {{ $directory }}</h3>
                                </div>
                                <form action="{{ Route('uploadfile') }}" method="post" enctype="multipart/form-data"
                                    class="flex flex-col gap-2">
                                    @csrf
                                    <label for="upload_file">Cargar Nuevo Archivo</label>
                                    <input type="file" name="upload_file" id="upload_file"
                                        class="h-12 block w-full py-2 px-4 bg-gray-100 border border-gray-300 rounded-lg focus:outline-none focus:border-blue-400">
                                    <input type="hidden" name="directory" value="{{ $directory }}">
                                    <button type="submit" onclick="validarArchivo()"
                                        class="w-full bg-blue-500 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded-lg">
                                        <i class="fas fa-cloud-upload-alt"></i> Subir
                                    </button>
                                </form>
                            </div>
                        </div>
                        <div class="w-full">
                            <div class="flex flex-col bg-white overflow-hidden rounded-lg p-6 gap-3">
                                <div>
                                    <h3 class="text-xl font-semibold">Crear Directorio en: {{ $directory }}</h3>
                                </div>
                                <form action="{{ Route('new_directory') }}" method="post"
                                    enctype="multipart/form-data" class="flex flex-col gap-2">
                                    @csrf
                                    <label for="folder" class="block">Nuevo Directorio</label>
                                    <input type="text" name="folder" id="folder"
                                        class="h-12 block w-full py-2 px-4 bg-gray-100 border border-gray-300 rounded-lg focus:outline-none focus:border-blue-400">
                                    <input type="hidden" name="directory" value="{{ $directory }}">
                                    <button type="submit"
                                        class="w-full bg-green-500 hover:bg-green-700 text-white font-bold py-2 px-4 rounded-lg">
                                        <i class="fas fa-folder-plus"></i> Crear
                                    </button>
                                </form>
                            </div>
                        </div>
                    </div>
                @endif
            </div>

        </div>
    </div>


    <script>
        /**
         * This function copies the provided file text to the clipboard.
         * 
         * @param {string} fileText - The text to be copied.
         */
        function copyToClipboard(fileText) {
            const content = fileText.replace('discorepo001', '/Repositorio');;
            const tempInput = document.createElement('textarea');
            tempInput.value = content;
            document.body.appendChild(tempInput);
            tempInput.select();
            document.execCommand('copy');
            document.body.removeChild(tempInput);
            alert('¡Contenido copiado al portapapeles!');
        }

        /**
         * This function generates a full URL link based on the provided file path and root URL,
         * then displays the generated URL in an alert.
         * 
         * @param {string} archivo - The file path.
         * @param {string} ruta - The root URL.
         */
        function enlace(archivo, ruta) {

            let filePath = archivo.replace('discorepo001/Repositorio/', 'http://smlms.sena.edu.co/');

            alert(filePath);

        }

        /**
         * This function adds a hidden input field to a form dynamically,
         * then sets its value to the provided file path, and submits the form.
         *
         * @param {string} archivo - The file path to be submitted with the form.
         */
        function selectFile(archivo) {
            const fileInput = document.createElement('input');
            fileInput.type = 'hidden';
            fileInput.name = 'currentPath';
            fileInput.value = archivo;

            const form = document.getElementById('formSelectFile');
            form.appendChild(fileInput);
            form.submit();
        }

        /**
         * This function validates the size of the uploaded file.
         * If the file size exceeds the maximum limit of 1GB, it displays an error message using SweetAlert.
         * Otherwise, it submits the form containing the file input.
         */
        function validarArchivo() {
            const fileInput = document.getElementById('upload_file');
            const file = fileInput.files[0];
            const tamanioMaximo = 1 * 1024 * 1024 * 1024; // 1GB en bytes

            if (file && file.size > tamanioMaximo) {
                Swal.fire({
                    icon: "error",
                    title: "Oops...",
                    text: "El tamaño del archivo supera el límite de 1GB",
                });
            } else {
                document.querySelector('form').submit();
            }
        }

         /*
         * This function adds event listeners to all elements with the class 'unzipButton
         */
     function addUnzipButtonEvent() {
            document.querySelectorAll('.unzipButton').forEach(button => {
                button.addEventListener('click', function(event) {
                    event.preventDefault();

                    Swal.fire({
                        title: "Success",
                        text: "Su archivo se ha mandado a la cola de descompresión, puede visitar la bandeja de archivos para revisar el estado de su archivo",
                        icon: "success",
                        confirmButtonText: "Ir",
                        showConfirmButton: true,
                    }).then((result) => {
                        if (result.isConfirmed) {
                            const form = button.closest('form');
                            form.submit();
                    
                        }
                       
                    });
                });
            });
        }
        addUnzipButtonEvent();


    </script>
</x-app-layout>
