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

    @if (session('success'))
    <script>
        document.addEventListener('DOMContentLoaded', function(){
            Swal.fire({
                title: "Éxito!",
                text: "{{ session('success') }}",
                icon: "success"
            });
        })
    </script>
    @endif

    <div class="overflow-x-auto max-w-7xl mx-auto mt-10 rounded-lg">
        <form action="import-files" method="post" enctype="multipart/form-data" class="flex flex-col gap-2" id="formUploadFile">
            @csrf
            <label for="upload_file">Cargar archivo con formato CSV</label>
            <input type="file" name="upload_file" id="upload_file" required>
            <button type="submit" class="bg-blue-500 text-white px-4 py-2 rounded">Importar</button>
        </form>

        @if(isset($data) && $data->count())
        <table class="w-full mt-5 border-collapse border border-gray-200">
            <thead>
                <tr class="bg-gray-100">
                    @foreach($data[0] as $header)
                        <th class="border px-4 py-2">{{ $header }}</th>
                    @endforeach
                </tr>
            </thead>
            <tbody>
                @foreach($data as $row)
                    <tr>
                        @foreach($row as $cell)
                            <td class="border px-4 py-2">{{ $cell }}</td>
                        @endforeach
                    </tr>
                @endforeach
            </tbody>
        </table>

        <!-- Paginación -->
        <div class="mt-4">
            {{ $data->links() }}
        </div>
        @else
        <p class="mt-4 text-red-500">No hay datos para mostrar.</p>
        @endif
    </div>
</x-app-layout>
