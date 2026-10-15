<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>@yield('titulo') · Planazo</title>

    {{-- Estilos globales de la app --}}
    <link rel="stylesheet" href="{{ asset('css/app.css') }}">

    {{-- NProgress: barra de carga en la parte superior --}}
    <link rel="stylesheet" href="https://unpkg.com/nprogress@0.2.0/nprogress.css">

    {{-- SweetAlert2: cuadros de confirmación bonitos --}}
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/sweetalert2@11/dist/sweetalert2.min.css">

    {{-- Cada vista puede agregar su propio CSS extra aquí --}}
    @yield('estilos')
</head>
<body>

    {{-- Barra de navegación --}}
    <nav class="barra">
        <a href="{{ route('planes.listar') }}" class="marca">Planazo</a>

        <div class="barra-derecha">
            <span>{{ auth()->user()->name }}</span>

            {{-- Botón para cerrar sesión --}}
            <form method="POST" action="{{ route('salir') }}">
                @csrf
                <button type="submit" class="boton boton-claro">Salir</button>
            </form>
        </div>
    </nav>

    {{-- Contenido principal de la página --}}
    <main class="contenedor">

        {{-- Mensajes flash (éxito o error) que vienen del controlador --}}
        @if (session('exito'))
            <div class="alerta alerta-exito">{{ session('exito') }}</div>
        @endif

        @if (session('error'))
            <div class="alerta alerta-error">{{ session('error') }}</div>
        @endif

        {{-- Aquí va el contenido de cada vista --}}
        @yield('contenido')

    </main>

    {{-- NProgress (spinner de barra) --}}
    <script src="https://unpkg.com/nprogress@0.2.0/nprogress.js"></script>

    {{-- SweetAlert2 --}}
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

    {{-- Script global: spinner automático + confirmaciones con data-confirm --}}
    <script src="{{ asset('js/ui.js') }}"></script>

    {{-- Cada vista puede agregar scripts extra aquí --}}
    @yield('scripts')

</body>
</html>
