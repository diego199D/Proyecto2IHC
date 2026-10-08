<?php

namespace App\Http\Controllers;

use App\Models\Plan;
use Illuminate\Http\Request;

class PlanesController extends Controller
{
    // Mensajes de error en español (los usan guardar y actualizar)
    private $mensajes = [
        'nombre.required'       => 'Escribe el nombre del plan.',
        'organizador.required'  => 'Escribe el nombre del organizador.',
        'fecha_limite.required' => 'Elige la fecha límite.',
        'fecha_limite.date'     => 'La fecha no es válida.',
    ];

    // ---------- LISTAR (pantalla "Mis planes") ----------

    public function listar()
    {
        $planes = Plan::where('user_id', auth()->id())
                      ->orderBy('fecha_limite', 'asc')
                      ->get();

        return view('planes.listar', compact('planes'));
    }

    // ---------- GUARDAR (formulario del dialog) ----------

    public function guardar(Request $request)
    {
        $request->validate([
            'nombre'       => 'required|max:255',
            'organizador'  => 'required|max:255',
            'fecha_limite' => 'required|date',
        ], $this->mensajes);

        $plan = new Plan();
        $plan->nombre = $request->nombre;
        $plan->organizador = $request->organizador;
        $plan->fecha_limite = $request->fecha_limite;
        $plan->user_id = auth()->id();
        $plan->save();

        return redirect()->route('planes.listar')->with('exito', '¡Plan creado!');
    }

    // ---------- EDITAR y ACTUALIZAR ----------

    public function editar(Plan $plan)
    {
        return view('planes.editar', compact('plan'));
    }

    public function actualizar(Request $request, Plan $plan)
    {
        $request->validate([
            'nombre'       => 'required|max:255',
            'organizador'  => 'required|max:255',
            'fecha_limite' => 'required|date',
        ], $this->mensajes);

        $plan->nombre = $request->nombre;
        $plan->organizador = $request->organizador;
        $plan->fecha_limite = $request->fecha_limite;
        $plan->save();

        return redirect()->route('planes.listar')->with('exito', '¡Plan actualizado!');
    }

    // ---------- ELIMINAR ----------

    public function eliminar(Plan $plan)
    {
        // Restricción: un plan confirmado no se puede eliminar, primero hay que cancelarlo
        if ($plan->estado == 'confirmado') {
            return redirect()->route('planes.listar')
                             ->with('error', 'Un plan confirmado no puede eliminarse. Primero cancélalo.');
        }

        $plan->delete();
        return redirect()->route('planes.listar')->with('exito', 'Plan eliminado.');
    }

    // ---------- CAMBIAR ESTADO (botones Confirmar / Cancelar) ----------

    public function cambiarEstado(Request $request, Plan $plan)
    {
        $request->validate([
            'estado' => 'required|in:pendiente,confirmado,cancelado',
        ]);

        $plan->estado = $request->estado;
        $plan->save();

        return redirect()->route('planes.listar')->with('exito', 'El plan ahora está ' . $plan->estado . '.');
    }
}
