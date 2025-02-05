<x-app-layout>


    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Centro cambios') }}
        </h2>
    </x-slot>
    @if ($errors->any())
    <div class="alert alert-danger">
        <ul>
            @foreach ($errors->all() as $error)
                <li>{{ $error }}</li>
            @endforeach
        </ul>
    </div>
@endif

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-xl sm:rounded-lg p-6">
                <h2 class="text-lg font-semibold mb-2">Centro de Cambios:</h2>
                <div class="overflow-x-auto">
                    <table class="w-full rounded-lg overflow-hidden">
                        <thead class="bg-gray-200">
                            <tr>
                                <th class="py-2 px-4">Nombre de Archivo</th>
                                <th class="py-2 px-4">Nuevo Nombre</th>
                                <th class="py-2 px-4">Tipo</th>
                                <th class="py-2 px-4">Ruta</th>
                                <th class="py-2 px-4">Versión Anterior</th>
                                <th class="py-2 px-4">Versión Actual</th>
                                <th class="py-2 px-4">Acción</th>
                                <th class="py-2 px-4"></th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach ($versions as $version)
                                <tr>
                                    <td class="py-2 px-4 border">{{ $version->name_file . '.' . $version->type }}</td>
                                    <td class="py-2 px-4 border">{{ $version->new_name }}</td>
                                    <td class="py-2 px-4 border">{{ $version->type }}</td>
                                    <td class="py-2 px-4 border">{{ $version->route }}</td>
                                    <td class="py-2 px-4 border">{{ $version->old_version }}</td>
                                    <td class="py-2 px-4 border">{{ $version->new_version }}</td>
                                    <td class="py-2 px-4 border">{{ $version->action }}</td>
                                    <td class="py-2 px-4 border">
                                        <button class="bg-blue-500 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded" onclick="update({{$version->id}})">
                                            Restaurar
                                        </button>
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
    <script>

        function update(id){
            Swal.fire({
  title: "Seguro?",
  text: "Seguro de Restaurar el Archivo!",
  icon: "warning",
  showCancelButton: true,
  confirmButtonColor: "#3085d6",
  cancelButtonColor: "#d33",
  confirmButtonText: "Si!"
}).then((result) => {
  if (result.isConfirmed) {

    location.href = "{{Route('restorefile')}}?id=" + id

  }
});
        }
    </script>
</x-app-layout>
