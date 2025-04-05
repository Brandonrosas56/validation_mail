<x-guest-layout>
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@10"></script>
    
    <script>
        // Función que se ejecutará cuando el DOM esté listo
        function checkSessionExpiration() {
            // Verificar si hay error en la sesión o en la URL
            const urlParams = new URLSearchParams(window.location.search);
            const sessionExpired = urlParams.get('session_expired');
            const errorMessage = "{{ session('error') }}" || 'Tu sesión ha expirado. Por favor, inicia sesión nuevamente.';
            
            if (sessionExpired === 'true') {
                alertSessionExpired(errorMessage);
            } else if ("{{ session('error') }}") {
                if (errorMessage.includes('bloqueada')) {
                    alertBlocked();
                } else if (errorMessage.includes('expirado') || errorMessage.includes('expirada')) {
                    alertSessionExpired(errorMessage);
                }
            }
        }

        // Detectar página de error 419 y redirigir
        if (window.location.href.includes('/create-account') || window.location.href.includes('/validate-account')) {
            if (document.title.includes('419') || document.body.textContent.includes('PAGE EXPIRED')) {
                window.location.href = '/login?session_expired=true';
            }
        }

        // Ejecutar cuando el DOM esté completamente cargado
        document.addEventListener('DOMContentLoaded', checkSessionExpiration);

        function alertBlocked() {
            Swal.fire({
                icon: "warning",
                title: "Bloqueo",
                text: "Tu cuenta de usuario esta bloqueada, por favor comuniquese con el administrador",
                confirmButtonText: "OK",
                confirmButtonColor: "#3085d6",
                allowOutsideClick: false
            });
        }

        function alertSessionExpired(message) {
            Swal.fire({
                icon: "warning",
                title: "Sesión Expirada",
                text: message,
                confirmButtonText: "OK",
                confirmButtonColor: "#3085d6",
                allowOutsideClick: false,
                allowEscapeKey: false,
                allowEnterKey: false,
                timer: 5000,
                timerProgressBar: true,
                didOpen: () => {
                    Swal.getPopup().style.zIndex = 9999;
                }
            });
        }
    </script>

    <head>
        <link rel="icon" type="image/x-icon" href="{{ asset('favicon.ico') }}">   
    </head>

    <div class="flex justify-center h-screen w-screen items-center">
        <div class="flex w-fit gap-4 rounded-lg shadow h-fit">
            <div>
                <img class="w-96 h-full object-cover rounded-l-xl" src="./img/login-banner.jpeg" alt="">
            </div>
            <div class="w-96 p-4 my-auto">
                <div>
                    <img src="{{ asset('img/banner.png')}}" alt="Imagen arriba" class="w-full h-18">
                </div>

                <div class="h-20 rounded-full mx-auto flex items-center justify-center">
                    <h1 class="text-ld font-bold">Iniciar Sesión</h1>
                </div>

                <x-validation-errors class="mb-4" />

                @session('status')
                <div class="">
                    {{ $value }}
                </div>
                @endsession

                <form class="w-full flex flex-col gap-4" method="POST" action="{{ route('login-controller') }}">
                    @csrf

                    <div class="w-full">
                        <x-label for="email" value="{{ __('Email') }}" />
                        <x-input id="email" class="block mt-1 w-full max-w-full" type="email" name="email" :value="old('email')" required autofocus autocomplete="username" />
                    </div>

                    <div class="w-full">
                        <x-label for="password" value="{{ __('Password') }}" />
                        <x-input id="password" class="block mt-1 w-full max-w-full" type="password" name="password" required autocomplete="current-password" />
                    </div>

                    <div class="w-full">
                        <label for="remember_me" class="flex items-center">
                            <input id="remember_me" type="checkbox" name="remember" 
                                   class="!border-2 !border-black !important rounded h-4 w-4">
                            <span class="ms-2 text-sm text-gray-600">{{ __('Remember me') }}</span>
                        </label>
                    </div>

                    <div class="space-x-4 flex justify-center w-full max-w-sm mx-auto items-center">
                        <x-button class="w-fit">
                            {{ __('Log in') }}
                        </x-button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</x-guest-layout>

<style>
    .max-w-full {
        max-width: 100%;
        box-sizing: border-box;
    }

    .w-96 {
        width: 24rem;
        padding-left: 1rem;
        padding-right: 1rem;
    }
    form {
        width: 100%;
    }
    input[type="checkbox"] {
        appearance: auto !important;
        -webkit-appearance: auto !important;
    }
</style>