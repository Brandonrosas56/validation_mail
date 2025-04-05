<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <meta name="csrf-token" content="{{ csrf_token() }}">

        <!-- <title>{{ config('app.name', 'Laravel') }}</title> -->
        <title>CREACTIVA</title>
        
        <link rel="icon" type="image/x-icon" href="{{ asset('favicon.ico') }}?v=2">



        <!-- icons -->
        <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css" integrity="sha512-DTOQO9RWCH3ppGqcWaEA1BIZOC6xxalwEsw9c2QQeAIftl+Vegovlnee1c9QX4TctnWMn13TZye+giMm8e2LwA==" crossorigin="anonymous" referrerpolicy="no-referrer" />
        
        <!-- Fonts -->
        <link rel="preconnect" href="https://fonts.bunny.net">
        <link href="https://fonts.bunny.net/css?family=figtree:400,500,600&display=swap" rel="stylesheet" />
        <script src="https://cdn.tailwindcss.com"></script>

        <!-- Scripts -->
        @vite(['resources/css/app.css', 'resources/js/app.js'])

        <!-- Styles -->
        @livewireStyles
        <script src="https://cdn.jsdelivr.net/npm/sweetalert2@10"></script>

        <link href="https://cdn.datatables.net/1.12.1/css/jquery.dataTables.min.css" rel="stylesheet">

        <!-- Script de jQuery -->
        <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>

        <!-- Script de DataTables -->
        <script src="https://cdn.datatables.net/1.12.1/js/jquery.dataTables.min.js"></script>

        <script>
            // Función para refrescar el token CSRF
            function refreshCsrfToken() {
                fetch('/refresh-csrf', {
                    method: 'POST',
                    headers: {
                        'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content
                    }
                })
                .then(response => response.json())
                .then(data => {
                    // Actualizar el token en todos los formularios y meta tags
                    document.querySelector('meta[name="csrf-token"]').content = data.token;
                    document.querySelectorAll('input[name="_token"]').forEach(input => {
                        input.value = data.token;
                    });
                })
                .catch(error => {
                    console.error('Error refreshing CSRF token:', error);
                    // Si hay un error, redirigir al login
                    window.location.href = '/login?session_expired=true';
                });
            }

            // Refrescar el token cada 30 minutos (1800000 ms)
            setInterval(refreshCsrfToken, 1800000);

            // También refrescar cuando la ventana recupera el foco
            document.addEventListener('visibilitychange', function() {
                if (document.visibilityState === 'visible') {
                    refreshCsrfToken();
                }
            });

            // Manejar errores de sesión expirada
            document.addEventListener('ajax:error', function(event) {
                if (event.detail && event.detail.status === 419) {
                    Swal.fire({
                        icon: 'warning',
                        title: 'Sesión Expirada',
                        text: 'Tu sesión ha expirado. Se intentará actualizar automáticamente.',
                        showConfirmButton: false,
                        timer: 3000
                    });
                    refreshCsrfToken();
                }
            });
        </script>
    </head>
    <body class="font-sans antialiased">
        <x-banner />

        <div class="min-h-screen bg-gray-100">
            @livewire('navigation-menu')

            <!-- Page Heading -->
            @if (isset($header))
                <header class="bg-white shadow">
                    <div class="max-w-7xl mx-auto py-6 px-4 sm:px-6 lg:px-8">
                        {{ $header }}
                    </div>
                </header>
            @endif

            <!-- Page Content -->
            <main>
                {{ $slot }}
            </main>
        </div>

        @stack('modals')

        @livewireScripts

        <!-- Script personalizado -->
        <script src="../../js/app.js"></script>
    </body>
</html>
