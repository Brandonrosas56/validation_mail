@php
use App\Models\CreateAccount;
$permissionSuperAdmin = auth()->user()->hasRoles('Super_admin');
@endphp

<x-app-layout>
    <link rel="stylesheet" href="{{ asset('css/ShowCreateAccount.css') }}">
    <x-slot name="title">
        Lista de Solicitudes
    </x-slot>

    <!-- Contenedor principal -->
    <div class="overflow-x-auto max-w-7xl mx-auto mt-10 rounded-lg">
        @include('forms.CreateAccount')
        <div class="mt-4 mb-4">
            <button onclick="toggleModal()" class="color text-white py-2 px-4 rounded-lg">Crear Cuenta</button>
        </div>

        <!-- Tabla de solicitudes -->
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
                    <th class="px-4 py-2 border-b">Número de Contrato</th>
                    <th class="px-4 py-2 border-b">Fecha de Inicio</th>
                    <th class="px-4 py-2 border-b">Fecha de Terminación</th>
                    <th class="px-4 py-2 border-b">Estado</th>
                    @if($permissionSuperAdmin)
                    <th class="px-4 py-2 border-b">Acción</th>
                    @endif
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
                    <td class="px-4 py-2 border-b">{{ $account->numero_contrato }}</td>
                    <td class="px-4 py-2 border-b">{{ $account->fecha_inicio_contrato }}</td>
                    <td class="px-4 py-2 border-b">
                        {{ $account->fecha_terminacion_contrato !== '1000-01-01' ? $account->fecha_terminacion_contrato : '' }}
                    </td>
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
                    @if($permissionSuperAdmin)
                    <td class="px-4 py-2 border-b">
                        <button class="bg-blue-500 p-2 rounded-md text-white"
                            onclick="toggleModalState({{ $account->id }}, {{ CreateAccount::CREATE_ACCOUNT }})">
                            <i class="fa fa-edit"></i>
                        </button>
                    </td>
                    @endif
                </tr>
                @endforeach
            </tbody>
        </table>

        <!-- Paginación -->
        <div class="mt-4 flex justify-center w-full">
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
            text: '{{ session('
            success ') }}',
            confirmButtonColor: '#00334f'
        });
        @elseif(session('error'))
        Swal.fire({
            icon: 'warning',
            title: 'Alerta',
            text: '{{ session('
            error ') }}',
            confirmButtonColor: '#00334f'
        });
        @elseif(session('error-modal'))
        toggleModal();
        Swal.fire({
            icon: 'warning',
            title: 'Alerta',
            text: '{{ session('
            error - modal ') }}',
            confirmButtonColor: '#00334f'
        });
        @endif
    </script>
</x-app-layout>