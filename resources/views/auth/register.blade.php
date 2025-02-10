<x-slot name="header">
    <h2 class="font-semibold text-xl text-gray-800 leading-tight">
        {{ __('Register') }}
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
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@10"></script>
    @if (session('success'))
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            Swal.fire({
                title: "Éxito!",
                text: "{{ session('success') }}",
                icon: "success"
            });
        });
    </script>
@endif

<a href="{{ route('login') }}" class="ml-4 font-semibold text-gray-600 hover:text-gray-900 focus:outline focus:outline-2 focus:rounded-sm focus:outline-red-500">Login</a>

<div class="flex justify-center items-center mt-8 bg-white">
    <div class="flex justify-center sm:flex-row flex-col sm:border-2 rounded-lg w-3/4 ">

        <!-- Form  roles -->
        <div class="flex  justify-center sm:px-6 rounded-lg  border-2 border-black-500" style=" background: #B5C4CB">
            <form method="POST" action="registerStore" id="formUser">
                @csrf
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
                    <x-label for="Select_regional" value="{{ __('Select_regional') }}" />
                    <x-select name="rgn_id" id="rgn_id" class="block mt-1 w-full">
                        <option value="">{{__('Select_regional')}}</option>
                        @foreach($regional as $region)
                        <option value="{{ $region->rgn_id }}">{{ $region->rgn_nombre }}</option>
                        @endforeach
                    </x-select>
                </div>

                <input id="password_confirmation" class="block mt-1 w-full" type="hidden" name="rol" value="Contratista" />

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
    </div>
</div>