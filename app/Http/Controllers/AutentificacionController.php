<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Password;

class AutentificacionController extends Controller
{
    // ---------- INGRESAR ----------

    public function mostrarIngreso()
    {
        return view('acceso.ingresar');
    }

    public function ingresar(Request $request)
    {
        $request->validate([
            'correo'     => 'required|email',
            'contrasena' => 'required',
        ], [
            'correo.required'     => 'Escribe tu correo.',
            'correo.email'        => 'El correo no es válido.',
            'contrasena.required' => 'Escribe tu contraseña.',
        ]);

        // Auth::attempt busca al usuario por correo y compara la contraseña
        $datos = [
            'email'    => $request->correo,
            'password' => $request->contrasena,
        ];

        if (Auth::attempt($datos)) {
            return redirect()->route('planes.listar');
        }

        return back()->with('error', 'El correo o la contraseña no son correctos.');
    }

    // ---------- REGISTRO ----------

    public function mostrarRegistro()
    {
        return view('acceso.registro');
    }

    public function registrar(Request $request)
    {
        $request->validate([
            'nombre'     => 'required|max:255',
            'correo'     => 'required|email|unique:users,email',
            'contrasena' => 'required|min:8|confirmed',
        ], [
            'nombre.required'      => 'Escribe tu nombre.',
            'correo.required'      => 'Escribe tu correo.',
            'correo.email'         => 'El correo no es válido.',
            'correo.unique'        => 'Ese correo ya está registrado.',
            'contrasena.required'  => 'Escribe una contraseña.',
            'contrasena.min'       => 'La contraseña debe tener al menos 8 caracteres.',
            'contrasena.confirmed' => 'Las contraseñas no coinciden.',
        ]);

        $usuario = new User();
        $usuario->name = $request->nombre;
        $usuario->email = $request->correo;
        $usuario->password = Hash::make($request->contrasena);
        $usuario->save();

        Auth::login($usuario);   // Entra directo después de registrarse

        return redirect()->route('planes.listar');
    }

    // ---------- RECUPERAR CONTRASEÑA (enviar el correo) ----------

    public function mostrarRecuperar()
    {
        return view('acceso.recuperar');
    }

    public function enviarCorreo(Request $request)
    {
        $request->validate([
            'correo' => 'required|email',
        ], [
            'correo.required' => 'Escribe tu correo.',
            'correo.email'    => 'El correo no es válido.',
        ]);

        // Laravel crea el token y manda el correo con el enlace
        // Si Gmail falla (datos de .env mal puestos), mostramos un mensaje en vez de la pantalla de error
        try {
            $estado = Password::sendResetLink(['email' => $request->correo]);
        } catch (\Exception $e) {
            return back()->with('error', 'No se pudo enviar el correo. Revisa la configuración de correo.');
        }

        if ($estado == Password::RESET_LINK_SENT) {
            return back()->with('exito', 'Te enviamos un enlace a tu correo.');
        }

        return back()->with('error', 'No existe una cuenta con ese correo.');
    }

    // ---------- RESTABLECER CONTRASEÑA (desde el enlace del correo) ----------

    public function mostrarRestablecer(Request $request, $token)
    {
        $correo = $request->email;   // Viene en el enlace: ?email=...
        return view('acceso.restablecer', compact('token', 'correo'));
    }

    public function restablecer(Request $request)
    {
        $request->validate([
            'correo'     => 'required|email',
            'contrasena' => 'required|min:8|confirmed',
        ], [
            'correo.required'      => 'Escribe tu correo.',
            'contrasena.required'  => 'Escribe la nueva contraseña.',
            'contrasena.min'       => 'La contraseña debe tener al menos 8 caracteres.',
            'contrasena.confirmed' => 'Las contraseñas no coinciden.',
        ]);

        $datos = [
            'email'                 => $request->correo,
            'password'              => $request->contrasena,
            'password_confirmation' => $request->contrasena_confirmation,
            'token'                 => $request->token,
        ];

        // Laravel revisa que el token sea válido y luego ejecuta esta función
        $estado = Password::reset($datos, function ($usuario, $contrasena) {
            $usuario->password = Hash::make($contrasena);
            $usuario->save();
        });

        if ($estado == Password::PASSWORD_RESET) {
            return redirect()->route('login')->with('exito', 'Contraseña cambiada. Ya puedes ingresar.');
        }

        return back()->with('error', 'El enlace no es válido o ya expiró. Pide uno nuevo.');
    }

    // ---------- SALIR ----------

    public function salir()
    {
        Auth::logout();
        return redirect()->route('login');
    }
}
