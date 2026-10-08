@extends('layouts.app')

@section('titulo', 'Editar plan')

@section('estilos')
    <link rel="stylesheet" href="{{ asset('css/planes/editar.css') }}">
@endsection

@section('contenido')
<div class="tarjeta">
    <h1>Editar plan</h1>

    <form method="POST" action="{{ route('planes.actualizar', $plan) }}">
        @csrf
        @method('PUT')

        <div class="campo">
            <label for="nombre">Nombre del plan</label>
            <input type="text" id="nombre" name="nombre" value="{{ old('nombre', $plan->nombre) }}">
            @error('nombre')
                <span class="campo-error">{{ $message }}</span>
            @enderror
        </div>

        <div class="campo">
            <label for="organizador">Organizador</label>
            <input type="text" id="organizador" name="organizador" value="{{ old('organizador', $plan->organizador) }}">
            @error('organizador')
                <span class="campo-error">{{ $message }}</span>
            @enderror
        </div>

        <div class="campo">
            <label for="fecha_limite">Fecha límite</label>
            <input type="date" id="fecha_limite" name="fecha_limite"
                   value="{{ old('fecha_limite', $plan->fecha_limite->format('Y-m-d')) }}">
            @error('fecha_limite')
                <span class="campo-error">{{ $message }}</span>
            @enderror
        </div>

        <div class="botones">
            <a href="{{ route('planes.listar') }}" class="boton boton-claro">Cancelar</a>
            <button type="submit" class="boton">Actualizar</button>
        </div>
    </form>
</div>
@endsection
