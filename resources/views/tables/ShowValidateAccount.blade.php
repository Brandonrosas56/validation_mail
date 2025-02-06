<x-app-layout>
    <link rel="stylesheet" href="{{ asset('css/ShowValidateAccount.css') }}">
    <x-slot name="title">
        Lista de Solicitudes
    </x-slot>

    <div class="overflow-x-auto max-w-7xl mx-auto mt-10 rounded-lg">
        @include('forms.ActivateAccount')
        <div class="mt-4 mb-4">
            <button onclick="toggleActivationModal()" class="color text-white py-2 px-4 rounded-lg">Activar Cuenta</button>
        </div>
        
        <table class="min-w-full bg-white shadow-md">
            <thead>
                <tr class="bg-gray-100 text-gray-700 text-sm">
                    <th class="px-4 py-2 border-b">#</th>
                    <th class="px-4 py-2 border-b">Regional</th>
                    <th class="px-4 py-2 border-b">Primer Nombre</th>
                    <th class="px-4 py-2 border-b">Segundo Nombre</th>
                    <th class="px-4 py-2 border-b">Primer Apellido</th>
                    <th class="px-4 py-2 border-b">Segundo Apellido</th>
                    <th class="px-4 py-2 border-b">Correo Personal</th>
                    <th class="px-4 py-2 border-b">Correo Institucional</th>
                    <th class="px-4 py-2 border-b">Número de Contrato</th>
                    <th class="px-4 py-2 border-b">Fecha de Inicio</th>
                    <th class="px-4 py-2 border-b">Fecha de Terminación</th>
                    <th class="px-4 py-2 border-b">Usuario</th>
                </tr>
            </thead>
            <tbody>
                @foreach ($accounts as $account)
                    <tr class="text-sm text-gray-700 odd:bg-white even:bg-[#D9D9D9]">
                        <td class="px-4 py-2 border-b">{{ $account->id }}</td>
                        <td class="px-4 py-2 border-b">{{ $account->regional }}</td>
                        <td class="px-4 py-2 border-b">{{ $account->primer_nombre }}</td>
                        <td class="px-4 py-2 border-b">{{ $account->segundo_nombre }}</td>
                        <td class="px-4 py-2 border-b">{{ $account->primer_apellido }}</td>
                        <td class="px-4 py-2 border-b">{{ $account->segundo_apellido }}</td>
                        <td class="px-4 py-2 border-b">{{ $account->correo_personal }}</td>
                        <td class="px-4 py-2 border-b">{{ $account->correo_institucional }}</td>
                        <td class="px-4 py-2 border-b">{{ $account->numero_contrato }}</td>
                        <td class="px-4 py-2 border-b">{{ $account->fecha_inicio_contrato }}</td>
                        <td class="px-4 py-2 border-b">{{ $account->fecha_terminacion_contrato }}</td>
                        <td class="px-4 py-2 border-b">{{ $account->usuario }}</td>
                    </tr>
                @endforeach
            </tbody>
        </table>
    </div>
</x-app-layout>
