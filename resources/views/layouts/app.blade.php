<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>@yield('titulo') · Planazo</title>
    <link rel="stylesheet" href="{{ asset('css/app.css') }}">
    @yield('estilos')   {{-- Cada vista agrega aquí su propio CSS --}}
</head>
<body>
    <nav class="barra">
        <a href="{{ route('planes.listar') }}" class="marca">Planazo</a>

        <div class="barra-derecha">
            <span>{{ auth()->user()->name }}</span>
            <form method="POST" action="{{ route('salir') }}">
                @csrf
                <button type="submit" class="boton boton-claro">Salir</button>
            </form>
        </div>
    </nav>

    <main class="contenedor">
        @include('layouts._partials.mensajes')

        @yield('contenido')
    </main>
</body>
</html>
