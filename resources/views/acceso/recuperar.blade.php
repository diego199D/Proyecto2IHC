@extends('layouts.acceso')

@section('titulo', 'Recuperar contraseña')

@section('contenido')
<header class="encabezado">
    <span class="logo">✓</span>
    <h1>Recuperar contraseña</h1>
    <p>Te enviaremos un enlace a tu correo para crear una nueva.</p>
</header>

<form method="POST" action="{{ route('enviar.correo') }}" class="formulario" novalidate>
    @csrf

    <div class="campo">
        <label for="correo">Correo electrónico</label>
        <input type="email" id="correo" name="correo" value="{{ old('correo') }}"
               placeholder="tucorreo@ejemplo.com" class="@error('correo') invalido @enderror">
        @error('correo')
            <span class="campo-error">{{ $message }}</span>
        @enderror
    </div>

    <button type="submit" class="boton">Enviar enlace</button>
</form>

<footer class="pie">
    <a href="{{ route('login') }}">← Volver a ingresar</a>
</footer>
@endsection
