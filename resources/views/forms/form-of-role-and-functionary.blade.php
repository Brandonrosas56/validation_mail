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
        document.addEventListener('DOMContentLoaded', function() {
            Swal.fire({
                title: "Éxito!",
                text: "{{ session('success') }}",
                icon: "success"
            });
        })
    </script>
    @endif

    @php
   $validatPermissionsAssing = auth()->user()->hasRole('Super_admin');
    @endphp
    <link rel="stylesheet" href="{{ asset('css/ShowValidateAccount.css') }}">
    <div class="overflow-x-auto max-w-7xl mx-auto mt-8 rounded-lg">
        <div class="inline">
            <form method="POST" action="assign-role-functionary" id="formroleFunctionaryController">
                @csrf
                <div class="flex items-center mt-4">
                    @if ($validatPermissionsAssing)
                    <div class="">
                        <label for="{{__('Select_role')}}" class="block mb-2 TextColor font-bold">{{__('Select_role')}}</label>
                        <select name="select_role" id="select_role" class="px-4 py-2 border rounded-md w-64">
                            <option value="select_rol">{{__('Select_role')}}</option>
                            @foreach ($roles as $role)
                            @if($role->name !== 'Super_admin' and $role->name === 'Asistente')
                            <option value="{{$role->name}}">{{$role->name}}</option>
                            @endif
                            @endforeach
                        </select>
                    </div>

                    <div class="ml-4">
                        <label for="{{__('Select_functionary')}}" class="block mb-2 TextColor font-bold">{{__('Select_functionary')}}</label>
                        <select name="Select_functionary" id="Select_functionary" class="px-4 py-2 border rounded-md w-64">
                            <option value="Select_functionary">{{__('Select_functionary')}}</option>
                            <option value="Director">{{__('Director')}}</option>
                            <option value="Subdirector">{{__('Deputy_Director')}}</option>
                            <option value="Director de Area">{{__('Area_Director')}}</option>
                            <option value="Jefe de Oficina">{{__('Office_Manager')}}</option>
                        </select>
                    </div>

                    <div class="ml-4">
                        <button type="submit" name="function" value="assign" class="color text-white py-2 px-4 rounded-lg mt-8">
                            Asignar
                        </button>
                    </div>
                  
                    <div class="ml-4">
                        <button type="submit" name="function" value="lock" class="color text-white py-2 px-4 rounded-lg mt-8">
                            {{__('change_state')}}
                        </button>
                    </div>
                    <div class="ml-4 mt-8  ">
                        <input type="text" placeholder="Buscar..." class="px-4 py-2 border rounded-md w-full max-w-xs" id="search-input">
                    </div>
                </div>
                @endif

                <div class="mt-8">
                    <table id="users-table" class="w-full bg-white shadow-md display overflow-y-auto max-h-full">
                        <thead>
                            <tr class="bg-gray-100 text-gray-700 text-sm">
                                <th class="px-1 py-2 border-b text-center">
                                    {{__('Select_all')}}
                                    <input type="checkbox" id="select-all" class="w-4 h-4 ml-2">
                                </th>
                                <th class="px-4 py-2 border-b">Regional</th>
                                <th class="px-4 py-2 border-b">Nombre</th>
                                <th class="px-4 py-2 border-b">Cédula</th>
                                <th class="px-4 py-2 border-b">Rol</th>
                                <th class="px-4 py-2 border-b">Cargo de funcionario</th>
                                <th class="px-4 py-2 border-b">bloqueo</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach ($users as $user)
                            @if($user->name !== 'CA')
                            <tr class="text-sm text-gray-700 odd:bg-white even:bg-[#D9D9D9]">
                                <td class="px-4 py-2 border-b" style="display: none;">{{ $user->id }}</td>
                                <td class="px-1 py-2 border-b text-center">
                                    <input type="checkbox" name="user_check[]" value="{{ $user->id }}" class="user-checkbox w-4 h-4">
                                </td>
                                <td class="px-4 py-2 border-b text-center">{{ $user->regional ? $user->regional->rgn_nombre : 'No asignado' }}</td>
                                <td class="px-4 py-2 border-b">{{ $user->name }}</td>
                                <td class="px-4 py-2 border-b text-center">{{ $user->supplier_document }}</td>
                                <td class="px-4 py-2 border-b text-cente">{{$user->roles->first()->name}}</td>
                                <td class="px-4 py-2 border-b text-center">{{ $user->position}}</td>
                                <td class="px-4 py-2 border-b text-center">{{ $user->lock ? 'bloqueado' : 'No bloqueado' }}</td>
                            </tr>
                            @endif
                            @endforeach
                        </tbody>
                    </table>
                </div>
            </form>
        </div>
    </div>
</x-app-layout>

<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<script>
    $(document).ready(function() {
        $('#select-all').click(function() {
            $('.user-checkbox').prop('checked', this.checked);
        });

        $('.user-checkbox').change(function() {
            if (!$(this).prop('checked')) {
                $('#select-all').prop('checked', false);
            }
        });
    });
</script>

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

<script>
    $(document).ready(function() {
        $('#search-input').on('keyup', function() {
            var value = $(this).val().toLowerCase();
            $('#users-table tbody tr').filter(function() {
                $(this).toggle($(this).text().toLowerCase().indexOf(value) > -1)
            });
        });
    });
</script>