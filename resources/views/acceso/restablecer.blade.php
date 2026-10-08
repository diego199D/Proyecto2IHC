@extends('layouts.acceso')

@section('titulo', 'Nueva contraseña')

@section('contenido')
<header class="encabezado">
    <span class="logo">✓</span>
    <h1>Nueva contraseña</h1>
    <p>Escribe tu nueva contraseña.</p>
</header>

<form method="POST" action="{{ route('restablecer') }}" class="formulario" novalidate>
    @csrf

    {{-- El token viene en el enlace del correo, lo mandamos escondido --}}
    <input type="hidden" name="token" value="{{ $token }}">

    <div class="campo">
        <label for="correo">Correo electrónico</label>
        <input type="email" id="correo" name="correo" value="{{ old('correo', $correo) }}"
               class="@error('correo') invalido @enderror">
        @error('correo')
            <span class="campo-error">{{ $message }}</span>
        @enderror
    </div>

    <div class="campo">
        <label for="contrasena">Nueva contraseña</label>
        <input type="password" id="contrasena" name="contrasena"
               placeholder="Mínimo 8 caracteres" class="@error('contrasena') invalido @enderror">
        @error('contrasena')
            <span class="campo-error">{{ $message }}</span>
        @enderror
    </div>

    <div class="campo">
        <label for="contrasena_confirmation">Confirmar contraseña</label>
        <input type="password" id="contrasena_confirmation" name="contrasena_confirmation"
               placeholder="Repite la contraseña">
    </div>

    <button type="submit" class="boton">Cambiar contraseña</button>
</form>
@endsection
