@extends('layouts.acceso')

@section('titulo', 'Crear cuenta')

@section('contenido')
<header class="encabezado">
    <span class="logo">✓</span>
    <h1>Crear cuenta</h1>
    <p>Empieza a crear tus planes.</p>
</header>

<form method="POST" action="{{ route('registrar') }}" class="formulario" novalidate>
    @csrf

    <div class="campo">
        <label for="nombre">Nombre</label>
        <input type="text" id="nombre" name="nombre" value="{{ old('nombre') }}"
               placeholder="Tu nombre" class="@error('nombre') invalido @enderror">
        @error('nombre')
            <span class="campo-error">{{ $message }}</span>
        @enderror
    </div>

    <div class="campo">
        <label for="correo">Correo electrónico</label>
        <input type="email" id="correo" name="correo" value="{{ old('correo') }}"
               placeholder="tucorreo@ejemplo.com" class="@error('correo') invalido @enderror">
        @error('correo')
            <span class="campo-error">{{ $message }}</span>
        @enderror
    </div>

    <div class="campo">
        <label for="contrasena">Contraseña</label>
        <input type="password" id="contrasena" name="contrasena"
               placeholder="Mínimo 8 caracteres" class="@error('contrasena') invalido @enderror">
        @error('contrasena')
            <span class="campo-error">{{ $message }}</span>
        @enderror
    </div>

    <div class="campo">
        <label for="contrasena_confirmation">Confirmar contraseña</label>
        {{-- La regla 'confirmed' compara 'contrasena' con 'contrasena_confirmation' --}}
        <input type="password" id="contrasena_confirmation" name="contrasena_confirmation"
               placeholder="Repite la contraseña">
    </div>

    <button type="submit" class="boton">Registrarme</button>
</form>

<footer class="pie">
    ¿Ya tienes cuenta? <a href="{{ route('login') }}">Ingresa</a>
</footer>
@endsection
