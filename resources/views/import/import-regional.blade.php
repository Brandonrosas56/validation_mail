<x-app-layout>
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@10"></script>
    @if ($errors->has('error'))
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            var errorMessage = "{{ $errors->first('error') }}";
            if (errorMessage) {
                folderEmpty(errorMessage);
            }
        });
    </script>
    @endif

    <div class="overflow-x-auto max-w-7xl mx-auto mt-10 rounded-lg">
        <form action="import-regional" method="post" enctype="multipart/form-data" class="flex flex-col gap-2" id="formUpluadFile">
            @csrf
            <label for="upload_file">Cargar archivo regionales</label>
            <input type="file" name="upload_file" id="upload_file" class="h-12 block w-full py-2 px-4 bg-gray-100 border border-gray-300 rounded-lg focus:outline-none focus:border-blue-400">
            <select name="formatCSV" id=formatCSV class="h-12 block w-full py-2 px-4 bg-gray-100 border border-gray-300 rounded-lg focus:outline-none focus:border-blue-400">
                <option value="formatIMS">Formato CSV</option>
            </select>
            <button type="submit" class="flex flex-row w-full justify-center px-6 py-2 text-lg rounded-lg" style="background-color:#04324D; color: #FFFFFF;">
                Subir archivo
            </button>
        </form>
    </div>
</x-app-layout>

<script>
    function folderEmpty(message) {
        Swal.fire({
            icon: "warning",
            title: "Aviso",
            text: message,
            button: "OK"
        });
    }
</script>