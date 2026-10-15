<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>@yield('titulo') · Planazo</title>
    <link rel="stylesheet" href="{{ asset('css/acceso.css') }}">
</head>
<body>

    {{-- Página centrada verticalmente --}}
    <main class="pagina-acceso">

        {{-- Tarjeta blanca con el formulario --}}
        <div class="tarjeta-acceso">

            {{-- Mensajes flash (éxito o error) --}}
            @if (session('exito'))
                <div class="alerta alerta-exito">{{ session('exito') }}</div>
            @endif

            @if (session('error'))
                <div class="alerta alerta-error">{{ session('error') }}</div>
            @endif

            {{-- Contenido de la vista (ingresar, registro, recuperar, restablecer) --}}
            @yield('contenido')

        </div>
    </main>

</body>
</html>
