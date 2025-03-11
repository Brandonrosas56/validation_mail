<nav class="bg-alternate-background border-b border-gray-100">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 flex items-center justify-between h-16">
        <!-- Logo y Título -->
        <div class="flex items-center gap-3">
            <a href="{{ route('dashboard') }}" class="w-6 h-6 no-underline"></a>
            <img src="{{ asset('img/logo_blanco.png') }}" alt="CREACTIVA" class="h-12 w-auto mx-auto">

        </div>

        @php
            $permissionsImport = auth()->user()->hasRole('Super_admin') || auth()->user()->hasRole('Admin');
            $permissionsAcces = auth()->user()->hasRole('Super_admin');
        @endphp

        <!-- Menú principal -->
        <div class="hidden sm:flex items-center justify-center flex-grow">
            <div class="flex gap-6">
                <x-nav-link href="{{ route('dashboard') }}" :active="request()->routeIs('dashboard')"
                    class="no-underline">
                    {{ __('Dashboard') }}
                </x-nav-link>
                @if ($permissionsAcces)
                    <x-nav-link href="{{ route('show-import') }}" :active="request()->routeIs('show-import')"
                        class="no-underline">
                        {{ __('show-import') }}
                    </x-nav-link>
                @endif

                @if($permissionsImport)
                    <x-nav-link href="{{ route('show_role_functionary') }}"
                        :active="request()->routeIs('show_role_functionary')" class="no-underline">
                        {{ __('show_role_functionary') }}
                    </x-nav-link>
                    <x-nav-link href="{{ route('show_user_authorization') }}"
                        :active="request()->routeIs('show_user_authorization')" class="no-underline">
                        {{ __('show_user_authorization') }}
                    </x-nav-link>
                @endif
            </div>
        </div>

        <!-- Menú usuario -->
        <div class="flex items-center gap-4">
            <!-- Menú desplegable de usuario -->
            <div class="relative">
                <x-dropdown align="right" width="48">
                    <x-slot name="trigger">
                        <button
                            class="flex text-sm border-2 border-transparent rounded-full focus:outline-none focus:border-gray-300 transition">
                            <img class="h-8 w-8 rounded-full object-cover" src="{{ Auth::user()->profile_photo_url }}"
                                alt="{{ Auth::user()->name }}" />
                        </button>
                    </x-slot>

                    <x-slot name="content">
                        <!-- Formulario oculto de logout -->
                        <form id="logout-form" method="POST" action="{{ route('logout') }}" class="hidden">
                            @csrf
                        </form>

                        <!-- Botón de cierre de sesión -->
                        <button type="button"
                            class="w-full text-left px-4 py-2 text-sm text-gray-700 hover:bg-gray-100 block"
                            onclick="document.getElementById('logout-form').submit();">
                            Cerrar Sesión
                        </button>
                    </x-slot>
                </x-dropdown>
            </div>

            <!-- Menú hamburguesa para móviles -->
            <div class="-me-2 flex items-center sm:hidden">
                <button onclick="toggleMobileMenu()"
                    class="inline-flex items-center justify-center p-2 rounded-md text-gray-400 hover:text-gray-500 hover:bg-gray-100 focus:outline-none transition">
                    <svg class="h-6 w-6" stroke="currentColor" fill="none" viewBox="0 0 24 24">
                        <path id="menu-open" class="inline-flex" stroke-linecap="round" stroke-linejoin="round"
                            stroke-width="2" d="M4 6h16M4 12h16M4 18h16" />
                        <path id="menu-close" class="hidden" stroke-linecap="round" stroke-linejoin="round"
                            stroke-width="2" d="M6 18L18 6M6 6l12 12" />
                    </svg>
                </button>
            </div>
        </div>
    </div>

    <!-- Menú responsive para móviles -->
    <div id="mobile-menu" class="hidden sm:hidden">
        <div class="pt-2 pb-3 space-y-1">
            <x-responsive-nav-link href="{{ route('dashboard') }}" :active="request()->routeIs('dashboard')"
                class="no-underline">
                {{ __('Dashboard') }}
            </x-responsive-nav-link>
        </div>

        <!-- Botón de cierre de sesión en móviles -->
        <form id="logout-form-mobile" method="POST" action="{{ route('logout') }}" class="hidden">
            @csrf
        </form>

        <button type="button" class="w-full text-left px-4 py-2 text-sm text-gray-700 hover:bg-gray-100 block"
            onclick="document.getElementById('logout-form-mobile').submit();">
            Salir Sesión
        </button>
    </div>
</nav>

<!-- Script para menú responsive -->
<script>
    function toggleMobileMenu() {
        const menu = document.getElementById('mobile-menu');
        const openIcon = document.getElementById('menu-open');
        const closeIcon = document.getElementById('menu-close');

        menu.classList.toggle('hidden');
        openIcon.classList.toggle('hidden');
        closeIcon.classList.toggle('hidden');
    }
</script>