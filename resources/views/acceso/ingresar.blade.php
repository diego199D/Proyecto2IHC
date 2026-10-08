@extends('layouts.acceso')

@section('titulo', 'Ingresar')

@section('contenido')
<header class="encabezado">
    <span class="logo">✓</span>
    <h1>Ingresar</h1>
    <p>Entra para ver tus planes.</p>
</header>

<form method="POST" action="{{ route('ingresar') }}" class="formulario" novalidate>
    @csrf

    <div class="campo">
        <label for="correo">Correo electrónico</label>
        <input type="email" id="correo" name="correo" value="{{ old('correo') }}"
               placeholder="tucorreo@ejemplo.com" class="@error('correo') invalido @enderror">
        @error('correo')
            <span class="campo-error">{{ $message }}</span>
        @enderror
    </div>

    <div class="campo">
        <div class="campo-fila">
            <label for="contrasena">Contraseña</label>
            <a href="{{ route('recuperar') }}" class="enlace-pequeno">¿La olvidaste?</a>
        </div>
        <input type="password" id="contrasena" name="contrasena"
               placeholder="••••••••" class="@error('contrasena') invalido @enderror">
        @error('contrasena')
            <span class="campo-error">{{ $message }}</span>
        @enderror
    </div>

    <button type="submit" class="boton">Entrar</button>
</form>

<footer class="pie">
    ¿No tienes cuenta? <a href="{{ route('registro') }}">Regístrate</a>
</footer>
@endsection
