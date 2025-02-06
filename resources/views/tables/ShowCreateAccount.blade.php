<x-action-section>
    <x-slot name="title">
        Lista de Solicitudes
    </x-slot>

    <x-slot name="description">
        Visualiza todas las solicitudes de cuenta.
    </x-slot>

    <x-slot name="content">
        <div class="overflow-x-auto">
            <table class="min-w-full bg-white border border-gray-300 shadow-md rounded-lg">
                <thead>
                    <tr class="bg-gray-100 text-gray-700 text-sm">
                        <th class="px-4 py-2 border-b">#</th>
                        <th class="px-4 py-2 border-b">Regional</th>
                        <th class="px-4 py-2 border-b">Primer Nombre</th>
                        <th class="px-4 py-2 border-b">Segundo Nombre</th>
                        <th class="px-4 py-2 border-b">Primer Apellido</th>
                        <th class="px-4 py-2 border-b">Segundo Apellido</th>
                        <th class="px-4 py-2 border-b">Correo Personal</th>
                        <th class="px-4 py-2 border-b">Número de Contrato</th>
                        <th class="px-4 py-2 border-b">Fecha de Inicio</th>
                        <th class="px-4 py-2 border-b">Fecha de Terminación</th>
                        <th class="px-4 py-2 border-b">Acciones</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach ($accounts as $account)
                        <tr class="text-sm text-gray-700">
                            <td class="px-4 py-2 border-b">{{ $account->id }}</td>
                            <td class="px-4 py-2 border-b">{{ $account->regional }}</td>
                            <td class="px-4 py-2 border-b">{{ $account->primer_nombre }}</td>
                            <td class="px-4 py-2 border-b">{{ $account->segundo_nombre }}</td>
                            <td class="px-4 py-2 border-b">{{ $account->primer_apellido }}</td>
                            <td class="px-4 py-2 border-b">{{ $account->segundo_apellido }}</td>
                            <td class="px-4 py-2 border-b">{{ $account->correo_personal }}</td>
                            <td class="px-4 py-2 border-b">{{ $account->numero_contrato }}</td>
                            <td class="px-4 py-2 border-b">{{ $account->fecha_inicio_contrato }}</td>
                            <td class="px-4 py-2 border-b">{{ $account->fecha_terminacion_contrato }}</td>
                            <td class="px-4 py-2 border-b">
                                <!-- Acción -->
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    </x-slot>
</x-action-section>
