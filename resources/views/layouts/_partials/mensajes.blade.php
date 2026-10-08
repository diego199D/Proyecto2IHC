@if (session('exito'))
    <div class="alerta alerta-exito">{{ session('exito') }}</div>
@endif

@if (session('error'))
    <div class="alerta alerta-error">{{ session('error') }}</div>
@endif
