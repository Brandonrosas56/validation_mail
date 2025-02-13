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


    <link rel="stylesheet" href="{{ asset('css/ShowValidateAccount.css') }}">
    <div class="overflow-x-auto max-w-7xl mx-auto mt-8 rounded-lg">
        <div class="inline">
            <form method="POST" action="assign-role-functionary" id="formRoleFunctionary">
                @csrf
                <div class="flex items-center mt-4">
                    <div class="">
                        <label for="{{__('Select_role')}}" class="block mb-2 TextColor font-bold">{{__('Select_role')}}</label>
                        <select name="select_role" id="select_role" class="px-4 py-2 border rounded-md w-64">
                            <option value="select_rol">{{__('Select_role')}}</option>
                            @foreach ($roles as $role)
                                @if($role->name !== 'Super_admin')
                                    <option value="{{$role->name}}">{{$role->name}}</option>
                                @endif
                            @endforeach
                        </select>
                    </div>

                    <div class="ml-4">
                        <label for="{{__('Select_functionary')}}" class="block mb-2 TextColor font-bold">{{__('Select_functionary')}}</label>
                        <select name="Select_functionary" id="Select_functionary" class="px-4 py-2 border rounded-md w-64">
                            <option value="Select_functionary">{{__('Select_functionary')}}</option>
                            <option value="Funcionario">{{__('Functionary')}}</option>
                            <option value="Contratista">{{__('Contractor')}}</option>
                        </select>
                    </div>

                    <div class="ml-4">
                        <button type="submit" class="color text-white py-2 px-4 rounded-lg mt-8">
                            Asignar
                        </button>
                    </div>
                </div>
                <div class="mt-8">
                    <table class="w-full bg-white shadow-md">
                        <thead>
                            <tr class="bg-gray-100 text-gray-700 text-sm">
                                <!-- Reducir tamaño de la casilla "Seleccionar todos" -->
                                <th class="px-1 py-2 border-b text-center">
                                    {{__('Select_all')}}
                                    <input type="checkbox" id="select-all" class="w-2 h-4 ml-2">
                                </th>
                                <th class="px-4 py-2 border-b">Regional</th>
                                <th class="px-4 py-2 border-b">Nombre</th>
                                <th class="px-4 py-2 border-b">Cédula</th>
                                <th class="px-4 py-2 border-b">Cargo de funcionario</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach ($users as $user)
                            @if($user->name !== 'admin')
                            <tr class="text-sm text-gray-700 odd:bg-white even:bg-[#D9D9D9]">
                                <td class="px-4 py-2 border-b" style="display: none;">{{ $user->id }}</td>
                                <!-- Reducir tamaño de la celda de los checkboxes -->
                                <td class="px-1 py-2 border-b text-center">
                                    <input type="checkbox" name="user_check[]" value="{{ $user->id }}" class="user-checkbox w-4 h-4">
                                </td>
                                <td class="px-4 py-2 border-b text-center">{{ $user->regional ? $user->regional->rgn_nombre : 'No asignado' }}</td>
                                <td class="px-4 py-2 border-b">{{ $user->name }}</td>
                                <td class="px-4 py-2 border-b text-center">{{ $user->supplier_document }}</td>
                                <td class="px-4 py-2 border-b text-center">{{ $user->functionary }}</td>
                            </tr>
                            @endif
                            @endforeach
                        </tbody>
                    </table>

                    <div class="flex justify-between items-center mt-4">
                        <div>
                            <span class="text-sm text-gray-700">Mostrando {{ $users->firstItem() }} a {{ $users->lastItem() }} de {{ $users->total() }} resultados</span>
                        </div>

                        <div class="flex gap-2">
                            @if ($users->onFirstPage())
                                <button disabled class="px-4 py-2 bg-gray-300 text-gray-600 rounded-md">Anterior</button>
                            @else
                                <a href="{{ $users->previousPageUrl() }}" class="px-4 py-2 bg-blue-500 text-white rounded-md">Anterior</a>
                            @endif
                            @if ($users->hasMorePages())
                                <a href="{{ $users->nextPageUrl() }}" class="px-4 py-2 bg-blue-500 text-white rounded-md">Siguiente</a>
                            @else
                                <button disabled class="px-4 py-2 bg-gray-300 text-gray-600 rounded-md">Siguiente</button>
                            @endif
                        </div>
                    </div>
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