@extends('layouts.app')

@section('titulo', 'Editar plan')

@section('contenido')

{{-- Tarjeta centrada con el formulario de edición --}}
<div class="tarjeta-editar">
    <h1>Editar plan</h1>

    {{-- PUT porque estamos actualizando un recurso existente --}}
    <form method="POST" action="{{ route('planes.actualizar', $plan) }}">
        @csrf
        @method('PUT')

        <div class="campo">
            <label for="nombre">Nombre del plan</label>
            {{-- old() recupera el valor si hay errores de validación --}}
            <input type="text" id="nombre" name="nombre"
                   value="{{ old('nombre', $plan->nombre) }}"
                   placeholder="Nombre del plan">
            @error('nombre')
                <span class="campo-error">{{ $message }}</span>
            @enderror
        </div>

        <div class="campo">
            <label for="organizador">Organizador</label>
            <input type="text" id="organizador" name="organizador"
                   value="{{ old('organizador', $plan->organizador) }}"
                   placeholder="Nombre del organizador">
            @error('organizador')
                <span class="campo-error">{{ $message }}</span>
            @enderror
        </div>

        <div class="campo">
            <label for="fecha_limite">Fecha límite</label>
            {{-- format('Y-m-d') porque el input type=date espera ese formato --}}
            <input type="date" id="fecha_limite" name="fecha_limite"
                   value="{{ old('fecha_limite', $plan->fecha_limite->format('Y-m-d')) }}">
            @error('fecha_limite')
                <span class="campo-error">{{ $message }}</span>
            @enderror
        </div>

        <div class="botones-formulario">
            <a href="{{ route('planes.listar') }}" class="boton boton-claro">Cancelar</a>
            <button type="submit" class="boton">Actualizar</button>
        </div>
    </form>
</div>

@endsection
