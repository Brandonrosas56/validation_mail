<!-- audit.blade.php -->
<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Archivo Zip') }}
        </h2>
    </x-slot>

    </form>
    <!-- table audit -->
    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-xl sm:rounded-lg p-6">
                <div class="overflow-x-auto">
                    <table class="w-full rounded-lg overflow-hidden">
                        <thead class="bg-gray-200">
                            <tr>
                                <th class="py-2 px-4">id</th>
                                <th class="py-2 px-4">Archivo Zip</th>
                                <th class="py-2 px-4">Ruta</th>
                                <th class="py-2 px-4">Fecha Creación</th>
                                {{--  <th class="py-2 px-4">Fecha Actualización</th>  --}}
                                <th class="py-2 px-4">Estado</th>
                                <th class="py-2 px-4">Metadata</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach ($archivosZip as $archivoZip)
                                <tr>
                                    <td class="py-2 px-4 border">{{ $archivoZip->id }}</td>
                                    <td class="py-2 px-4 border">{{ $archivoZip->name }}</td>
                                    <td class="py-2 px-4 border">{{ $archivoZip->path }}</td>
                                    <td class="py-2 px-4 border">{{ $archivoZip->created_at }}</td>
                                    <td class="py-2 px-4 border">{{ $archivoZip->state}}</td>
                                    <td class="py-2 px-4 border">
                                        <form action="{{ route('metadata') }}" method="POST">
                                            @csrf
                                            <input type="hidden" name="archivoZipId" value="{{ $archivoZip->id }}">
                                            <button type="submit" class="ml-4 text-white bg-gray-800 hover:bg-gray-900 font-bold py-2 px-4 rounded">Metadata</button>
                                        </form>
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>

</x-app-layout>