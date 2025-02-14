<x-guest-layout>
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@10"></script>
    @if (session('error'))
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            alertBlocked();
        });
    </script>
    @endif
    <div class="flex justify-center h-screen w-screen items-center">
        <div class="flex w-fit gap-4 rounded-lg shadow h-fit">
            <div>
                <img class="w-96 h-full object-cover rounded-l-xl" src="./img/login-banner.jpeg" alt="">
            </div>
            <div class="w-96  p-4 my-auto">
                <div class="  h-20 rounded-full mx-auto flex items-center justify-center">
                    <h1 class="text-ld font-bold">Iniciar Sesión</h1>
                </div>

                <x-validation-errors class="mb-4" />

                @session('status')
                <div class="">
                    {{ $value }}
                </div>
                @endsession

                <form class="w-full flex flex-col gap-4" method="POST" action="{{ route('login') }}">
                    @csrf

                    <div>
                        <x-label for="email" value="{{ __('Email') }}" />
                        <x-input id="email" class="block mt-1 w-full" type="email" name="email" :value="old('email')" required autofocus autocomplete="username" />
                    </div>

                    <div class="w-full">
                        <x-label for="password" value="{{ __('Password') }}" />
                        <x-input id="password" class="block mt-1 w-full" type="password" name="password" required autocomplete="current-password" />
                    </div>

                    <div class="w-full">
                        <label for="remember_me" class="flex items-center">
                            <x-checkbox id="remember_me" name="remember" />
                            <span class="ms-2 text-sm text-gray-600">{{ __('Remember me') }}</span>
                        </label>
                    </div class="space-x-4 flex">
                    <div class="flex justify-between w-full max-w-sm mx-auto items-center">
                        @if (Route::has('register'))
                            <a href="{{ route('registerUsers') }}" class="px-4 py-2 bg-gray-800 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-gray-700 focus:bg-gray-700 active:bg-gray-900 focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:ring-offset-2 transition ease-in-out duration-150">
                                Registrar
                            </a>
                        @endif

                        <x-button class="w-fit">
                            {{ __('Log in') }}
                        </x-button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</x-guest-layout>

<script>
    function alertBlocked() {
        Swal.fire({
            icon: "warning",
            title: "Bloqueo",
            text: "Tu cuenta de usuario esta bloqueada, por favor comuniquese con el administrador",
            button: "OK"
        });
    }
</script>