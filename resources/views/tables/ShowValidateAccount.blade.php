@php
    use App\Models\CreateAccount;
@endphp
<x-app-layout>
    <link rel="stylesheet" href="{{ asset('css/ShowValidateAccount.css') }}">
    <x-slot name="title">
        Lista de Solicitudes
    </x-slot>

    <div class="overflow-x-auto max-w-7xl mx-auto mt-10 rounded-lg">
        @include('forms.ActivateAccount')
        <div class="mt-4 mb-4">
            <button onclick="toggleActivationModal()" class="color text-white py-2 px-4 rounded-lg">Activar
                Cuenta</button>
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
                    <th class="px-4 py-2 border-b">Estado</th>
                    <th class="px-4 py-2 border-b">Acción</th>
                </tr>
            </thead>
            <tbody>
                @foreach ($accounts as $account)
                    <tr class="text-sm text-gray-700 odd:bg-white even:bg-[#D9D9D9]">
                        <td class="px-4 py-2 border-b">{{ $account->id }}</td>
                        <td class="px-4 py-2 border-b">
                            {{ $account->regional ? $account->regional->rgn_nombre : 'No asignado' }}
                        </td>
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
                        <td class="px-4 py-2 border-b">
                            <div class="flex items-center gap-2">
                                <span class="w-3 h-3 rounded-full 
                                                    @if ($account->estado === 'pendiente') bg-yellow-500 
                                                    @elseif ($account->estado === 'fallido' || $account->estado === 'rechazado') bg-red-500 
                                                    @elseif ($account->estado === 'exitoso') bg-green-500 
                                                    @else bg-gray-400 @endif">
                                </span>
                                <span class="text-gray-700 text-sm">{{ $account->estado }}</span>
                            </div>
                        </td>
                        <td class="px-4 py-2 border-b "><button class=" bg-blue-500 p-2 rounded-md text-white"
                                onclick="toggleModalState({{ $account->id }},{{ CreateAccount::VALIDATE_ACCOUNT }})"><i
                                    class="fa fa-edit "></i></button></td>
                    </tr>
                @endforeach
            </tbody>
        </table>
        <div class="mt-4 flex juntify-center w-full">
            {{ $accounts->links() }}
        </div>
    </div>

    @include('forms.ChangeState')
    
    <script>
        // Verificar si hay mensajes de éxito o error
        @if(session('success'))
            Swal.fire({
                icon: 'success',
                title: 'Éxito',
                text: '{{ session('success') }}',
                confirmButtonColor: '#00334f'
            });
        @elseif(session('error'))
            Swal.fire({
                icon: 'warning',
                title: 'Alerta',
                text: '{{ session('error') }}',
                confirmButtonColor: '#00334f'
            });
        @elseif(session('error-modal'))
            toggleActivationModal();
            Swal.fire({
                icon: 'warning',
                title: 'Alerta',
                text: '{{ session('error-modal') }}',
                confirmButtonColor: '#00334f'
            });
        @endif
    </script>
</x-app-layout>