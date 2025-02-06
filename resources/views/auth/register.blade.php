<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Usuarios') }}
        </h2>
        <script>
            document.addEventListener('DOMContentLoaded', function() {
                editUser('', '', '', '');
            })

            //!clean the form
            function clearForm() {
                document.getElementById('formUser').reset();
            }
        </script>
    </x-slot>

    <div class="flex justify-center items-center mt-8 bg-white">
        <div class="flex justify-center sm:flex-row flex-col sm:border-2 rounded-lg w-3/4 ">

            <!-- Form  roles -->
            <div class="flex  justify-center sm:px-6 rounded-lg  border-2 border-black-500" style=" background: #B5C4CB">
                <form method="POST" action="registerStore" id="formUser">
                    @csrf
                    @method('PUT')
                    <x-validation-errors class="mb-4" />
                    <div>
                        <x-input id="id" name="id" type="hidden" :value="$id ?? ''"></x-input>
                    </div>
                    <div>
                        <x-label for="name" value="{{ __('Name') }}" class="mt-8" />
                        <x-input id="name" class="block mt-1 w-full" type="text" name="name" :value="old('name')" required autofocus autocomplete="name" />
                    </div>

                    <div class="mt-4">
                        <x-label for="email" value="{{ __('Email') }}" />
                        <x-input id="email" class="block mt-1 w-full" type="email" name="email" :value="old('email')" required autocomplete="username" />
                    </div>

                    <div class="mt-4">
                        <x-label for="password" value="{{ __('Password') }}" />
                        <x-input id="password" class="block mt-1 w-full" type="password" name="password" autocomplete="new-password" />
                    </div>

                    <div class="mt-4">
                        <x-label for="password_confirmation" value="{{ __('Confirm Password') }}" />
                        <x-input id="password_confirmation" class="block mt-1 w-full" type="password" name="password_confirmation" autocomplete="new-password" />
                    </div>
                    <div class="mt-4">
                        <x-select name="rgn_id" id="rgn_id" class="block mt-1 w-full">
                            <option value="">{{__('Select_regional')}}</option>
                            @foreach($regional as $region)
                                <option value="{{ $region->rgn_id }}">{{ $region->rgn_nombre }}</option>
                            @endforeach
                        </x-select>
                    </div>

                    <input id="password_confirmation" class="block mt-1 w-full" type="hiden" name="rol" value="Contratista" />

                    <div class="flex justify-between w-full mb-4">
                        <x-button class="mt-4 ">
                            {{ __('Register') }}
                        </x-button>
                        <x-onclick class="mt-4" onclick="clearForm()">
                            {{__('Clear')}}
                        </x-onclick>
                    </div>
                </form>
            </div>

            <div class="flex justify-center rounded-lg" style="background: #D9D9D9;">
                <div class="overflow-x-auto overflow-y-auto rounded-lg">
                    <table>
                        <thead class="bg-gray-200">
                            <tr>
                                <th style="display: none">{{__('id')}}</th>
                                <th class="py-2 px-4 text-white w-screen" style="background: #009F00;">{{__('Name')}}</th>
                                <th class="py-2 px-4 text-white w-screen" style="background: #00324D;">{{__('Email')}}</th>
                                <th class="py-2 px-4 text-white w-screen" style="background: #009F00;">{{__('Role')}}</th>
                                <th class="py-2 px-4 text-white w-screen" style="background: #00324D;">{{__('Regional')}}</th>
                                <th class="py-2 px-4 text-white w-screen" style="background: #009F00;">{{__('Edit')}}</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($users as $user)
                            <tr>
                                <td style="display: none">{{$user->id}}</td>
                                <td class="py-2 px-4 border bg-white text-center">{{$user->name}}</td>
                                <td class="py-2 px-4 border text-center" style="background: #B5C4CB;">{{$user->email}}</td>
                                <td class="py-2 px-4 border bg-white text-center">{{$user->roles->first()->name}}</td>
                                <td class="py-2 px-4 border text-center" style="background: #B5C4CB;">{{$user->regional ? $user->regional->rgn_nombre : 'N/A' }}</td>
                                <td class="py-2 px-4 border bg-white text-center">
                                    <button onclick="editUser('{{$user->id}}', '{{$user->name}}', 
                                '{{$user->email}}','{{$user->roles->first()->name}}')">
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
    </div>
</x-app-layout>