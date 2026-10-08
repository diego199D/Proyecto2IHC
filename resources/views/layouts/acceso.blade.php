<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>@yield('titulo') · Planazo</title>
    <link rel="stylesheet" href="{{ asset('css/acceso.css') }}">
</head>
<body>
    <main class="acceso">
        <div class="tarjeta">
            @include('layouts._partials.mensajes')

            @yield('contenido')
        </div>
    </main>
</body>
</html>
