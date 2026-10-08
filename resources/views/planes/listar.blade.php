@extends('layouts.app')

@section('titulo', 'Mis planes')

@section('estilos')
    <link rel="stylesheet" href="{{ asset('css/planes/listar.css') }}">
@endsection

@section('contenido')
<div class="encabezado">
    <h1>Mis planes</h1>
    <button class="boton" onclick="document.getElementById('dialogo').showModal()">+ Crear plan</button>
</div>

<table class="tabla">
    <thead>
        <tr>
            <th>Plan</th>
            <th>Organizador</th>
            <th>Fecha límite</th>
            <th>Estado</th>
            <th>Acciones</th>
        </tr>
    </thead>
    <tbody>
        @forelse ($planes as $plan)
        <tr>
            <td>{{ $plan->nombre }}</td>
            <td>{{ $plan->organizador }}</td>
            <td>{{ $plan->fecha_limite->format('d/m/Y') }}</td>
            <td><span class="estado estado-{{ $plan->estado }}">{{ $plan->estado }}</span></td>
            <td class="acciones">
                {{-- Confirmar o Cancelar, según el estado actual --}}
                <form method="POST" action="{{ route('planes.estado', $plan) }}">
                    @csrf
                    @method('PUT')
                    @if ($plan->estado == 'confirmado')
                        <input type="hidden" name="estado" value="cancelado">
                        <button type="submit" class="boton boton-claro">Cancelar</button>
                    @else
                        <input type="hidden" name="estado" value="confirmado">
                        <button type="submit" class="boton boton-claro">Confirmar</button>
                    @endif
                </form>

                <a href="{{ route('planes.editar', $plan) }}" class="boton boton-claro">Editar</a>

                {{-- Restricción: si está confirmado, no se puede eliminar --}}
                @if ($plan->estado == 'confirmado')
                    <button class="boton boton-rojo" disabled title="Cancela el plan para poder eliminarlo">Eliminar</button>
                @else
                    <form method="POST" action="{{ route('planes.eliminar', $plan) }}">
                        @csrf
                        @method('DELETE')
                        <button type="submit" class="boton boton-rojo" onclick="return confirm('¿Seguro que quieres eliminar el plan {{ $plan->nombre }}?')">Eliminar</button>
                    </form>
                @endif
            </td>
        </tr>
        @empty
            <tr><td colspan="5" class="vacio">Todavía no tienes planes. ¡Crea el primero!</td></tr>
        @endforelse
    </tbody>
</table>

{{-- Dialog con el formulario para crear un plan --}}
{{-- Si hubo errores al guardar, se abre solo para mostrarlos --}}
<dialog id="dialogo" {{ $errors->any() ? 'open' : '' }}>
    <h2>Nuevo plan</h2>

    <form method="POST" action="{{ route('planes.guardar') }}">
        @csrf

        <div class="campo">
            <label for="nombre">Nombre del plan</label>
            <input type="text" id="nombre" name="nombre" value="{{ old('nombre') }}">
            @error('nombre')
                <span class="campo-error">{{ $message }}</span>
            @enderror
        </div>

        <div class="campo">
            <label for="organizador">Organizador</label>
            <input type="text" id="organizador" name="organizador" value="{{ old('organizador') }}">
            @error('organizador')
                <span class="campo-error">{{ $message }}</span>
            @enderror
        </div>

        <div class="campo">
            <label for="fecha_limite">Fecha límite</label>
            <input type="date" id="fecha_limite" name="fecha_limite" value="{{ old('fecha_limite') }}">
            @error('fecha_limite')
                <span class="campo-error">{{ $message }}</span>
            @enderror
        </div>

        <div class="dialogo-botones">
            <button type="button" class="boton boton-claro" onclick="document.getElementById('dialogo').close()">Cancelar</button>
            <button type="submit" class="boton">Guardar</button>
        </div>
    </form>
</dialog>
@endsection
