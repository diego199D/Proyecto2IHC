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

{{-- Formulario de búsqueda y filtros --}}
<form method="GET" action="{{ route('planes.listar') }}" class="filtros">
    <input type="text" name="buscar" placeholder="Buscar" value="{{ $buscar }}">

    <select name="estado">
        <option value="">Todos los estados</option>
        <option value="pendiente"  {{ $estado === 'pendiente'  ? 'selected' : '' }}>Pendiente</option>
        <option value="confirmado" {{ $estado === 'confirmado' ? 'selected' : '' }}>Confirmado</option>
        <option value="cancelado"  {{ $estado === 'cancelado'  ? 'selected' : '' }}>Cancelado</option>
    </select>

    <select name="fecha">
        <option value="">Cualquier fecha</option>
        <option value="7dias"   {{ $fecha === '7dias'   ? 'selected' : '' }}>Próximos 7 días</option>
        <option value="vencidos" {{ $fecha === 'vencidos' ? 'selected' : '' }}>Ya vencidos</option>
    </select>

    <button type="submit" class="boton">Filtrar</button>

    @if ($buscar || $estado || $fecha)
        <a href="{{ route('planes.listar') }}" class="boton boton-claro">Limpiar</a>
    @endif
</form>

{{-- Chips de filtros activos --}}
@if ($buscar || $estado || $fecha)
<div class="filtros-activos">
    <span>Filtros activos:</span>

    @if ($buscar)
        <a href="{{ route('planes.listar', array_filter(['estado' => $estado, 'fecha' => $fecha])) }}" class="chip">
            "{{ $buscar }}" ×
        </a>
    @endif

    @if ($estado)
        <a href="{{ route('planes.listar', array_filter(['buscar' => $buscar, 'fecha' => $fecha])) }}" class="chip">
            {{ ucfirst($estado) }} ×
        </a>
    @endif

    @if ($fecha)
        <a href="{{ route('planes.listar', array_filter(['buscar' => $buscar, 'estado' => $estado])) }}" class="chip">
            {{ $fecha === '7dias' ? 'Próximos 7 días' : 'Ya vencidos' }} ×
        </a>
    @endif
</div>
@endif

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
            <td>
                <span class="estado estado-{{ $plan->estado }}">
                    @if ($plan->estado === 'pendiente')  Pendiente
                    @elseif ($plan->estado === 'confirmado')  Confirmado
                    @else  Cancelado
                    @endif
                </span>
            </td>
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

                <a href="{{ route('planes.editar', $plan) }}" class="boton boton-editar">Editar</a>

                {{-- Restricción: si está confirmado, no se puede eliminar --}}
                @if ($plan->estado == 'confirmado')
                    <button class="boton boton-rojo" disabled title="">Eliminar</button>
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
            <tr>
                <td colspan="5" class="vacio">
                    @if ($buscar || $estado || $fecha)
                        Ningún plan coincide con los filtros aplicados.
                    @else
                        Todavía no tienes planes. ¡Crea el primero!
                    @endif
                </td>
            </tr>
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
