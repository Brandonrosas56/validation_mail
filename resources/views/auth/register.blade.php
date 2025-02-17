
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <!-- <title>{{ config('app.name', 'Laravel') }}</title> -->
    <title>Zajuna - Version Control Manager</title>
    
    <!-- icons -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css" integrity="sha512-DTOQO9RWCH3ppGqcWaEA1BIZOC6xxalwEsw9c2QQeAIftl+Vegovlnee1c9QX4TctnWMn13TZye+giMm8e2LwA==" crossorigin="anonymous" referrerpolicy="no-referrer" />

    <!-- Fonts -->
    <link rel="preconnect" href="https://fonts.bunny.net">
    <link href="https://fonts.bunny.net/css?family=figtree:400,500,600&display=swap" rel="stylesheet" />

    <!-- Scripts -->
    @vite(['resources/css/app.css', 'resources/js/app.js'])

    <!-- Styles -->
    @livewireStyles
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    
</head>
    
<x-slot name="header">
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

<div class="flex items-center justify-center min-h-screen">
    <div class="w-96 max-w-md">

        <div class="flex justify-center mb-2">
            <img src="{{ asset('img/logo_sena_web.svg')}}" alt="Imagen arriba" class="w-20 h-20">
        </div>

        <!-- Form  roles -->
        <div >
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

                <div>
                    <x-label for="supplier_document" value="{{ __('Supplier_document') }}" class="mt-8" />
                    <x-input id="supplier_document" class="block mt-1 w-full" type="text" name="supplier_document" :value="old('supplier_document')" required />
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
                    <x-button class="mt-4">
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
