<x-app-layout>
<div class="mb-8">
    <x-slot name="header">
        
            <h2 class="font-semibold text-xl text-gray-800 leading-tight">
                {{ __('Roles') }}
            </h2>
        
        <script>
            document.addEventListener('DOMContentLoaded', function() {
                editRole('', '', '');
            })

            //!clear the form
            function clearForm() {
                document.getElementById('formRoles').reset();
            }

            //!Load data from selected row to form
            function editRole(id, name, permissions) {
                clearForm();

                document.getElementById('role_id').value = id;
                document.getElementById('name').value = name;

                var form = document.getElementById('formRoles');

                if (id) {

                    var permi = String(permissions)
                    const arrayPermissions = permi.split(',');

                    //Loop through the array
                    arrayPermissions.forEach(permission => {
                        if (permission.includes("Administrador de usuarios")) {
                            document.getElementById('admin_users').checked = true;
                        }
                        if (permission.includes("Administrador de archivos")) {
                            document.getElementById('admin_files').checked = true;
                        }
                        if (permission.includes("Vista de archivos")) {
                            document.getElementById('view_files').checked = true;
                        }
                        if (permission.includes("Administrador de auditoria")) {
                            document.getElementById('admin_audit').checked = true;
                        }
                    });
                    form.action = '/updateRoles/' + id;
                    form.querySelector('input[name="_method"]').value = 'PUT'
                } else {
                    form.action = 'registerRoles';
                    form.querySelector('input[name="_method"]').value = 'POST'
                }

                //convert permissions to a string
                if (typeof permissions != 'string') {
                    permissions = String(permissions);
                }
            }
        </script>
    </x-slot>
    </div>



    <div class="flex justify-center items-center  bg-white">
        <div class="flex justify-center sm:flex-row flex-col sm:border-2 rounded-lg w-3/4 ">

            <!-- Form  roles -->
            <div class="flex  justify-center sm:px-6 sm:w-3/4 rounded-lg  border-2 border-black-500" style=" background: #B5C4CB">
                <form method="POST" action="" id="formRoles">
                    @csrf
                    @method('PUT')

                    <x-validation-errors class="mb-4" />
                    <div class=" flex justify-center mt-6 mb-4 bg-white rounded">
                        <x-label class="mt-1 mb-1" value="{{__('Permissions')}}"></x-label>
                    </div>
                    <x-input id="role_id" name="role_id" type="hidden" :value="$role_id ?? ''"></x-input>
                    <div>
                        <x-input id="name" class="block mt-1 w-full" type="text" name="name" :value="old('name')" required autofocus autocomplete="name" placeholder="{{__('Name')}}" />
                    </div>
                    <div class="mt-4">
                        <x-checkbox id="admin_users" type="checkbox" name="permissions[]" value="admin_users" style="border-color: #009F00; " />
                        {{__('admin_users')}}
                    </div>
                    <div class="mt-4">
                        <x-checkbox id="admin_files" type="checkbox" name="permissions[]" value="admin_files" style="border-color: #009F00;" />
                        {{__('admin_files')}}
                    </div>
                    <div class="mt-4">
                        <x-checkbox id="view_files" type="checkbox" name="permissions[]" value="view_files" style="border-color: #009F00;" />
                        {{__('view_files')}}
                    </div>
                    <div class="mt-4">
                        <x-checkbox id="admin_audit" type="checkbox" name="permissions[]" value="admin_audit" style="border-color: #009F00;" />
                        {{__('admin_audit')}}
                    </div>

                    <div class="flex w-full justify-center mt-4 ">
                        <x-button style="background: #009F00;">
                            {{__('save') }}
                        </x-button>
                        <x-onclick onclick="clearForm()" style="background: #009F00;">
                            {{__('Clear')}}
                        </x-onclick>
                    </div>
                    <div class="mt-4"></div>
                </form>
            </div>

            <!-- Table roles -->
            <div class="flex overflow-x-auto justify-center w-full rounded-lg" style="background: #D9D9D9;">
                <div class="overflow-x-auto overflow-y-auto rounded-lg">
                    <table>
                        <thead class="bg-gray-200">
                            <tr>
                                <th class="py-2 px-4 text-white w-screen w-1/2" style="background: #009F00;">{{__('Name')}}</th>
                                <th class="py-2 px-4 text-white w-1/2" style="background: #00324D;">{{__('admin_users')}}</th>
                                <th class="py-2 px-4 text-white w-screen w-1/2" style="background: #009F00;">{{__('Edit')}}</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($names as $role)
                            <tr>
                                <td style="display: none;">{{ $role['id'] }}</td>
                                <td class="py-2 px-4 border bg-white text-center">{{ $role['name'] }}</td>
                                <td class="py-2 px-4 border text-center" style="background: #B5C4CB;">
                                    @foreach($role['permissions'] as $permission)
                                    {{ $permission }}<br>
                                    @endforeach
                                </td>
                                <td class="py-2 px-4 border bg-white text-center">
                                    <button class="flex items-center justify-center" onclick="editRole('{{ $role['id'] }}', '{{ $role['name'] }}',  {{ json_encode($role['permissions']) }})">
                                        <img src="{{ asset('img/Editar.webp') }}" alt="Editar">
                                    </button>
                                </td>
                            </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
</x-app-layout>