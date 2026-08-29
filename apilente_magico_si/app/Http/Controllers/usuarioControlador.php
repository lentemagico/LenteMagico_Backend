<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\usuarioModelo as Usuario;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\DB;

class usuarioControlador extends Controller
{
    public function index()
    {
        $usuario = Usuario::all();

        if ($usuario->isEmpty()) {
            $data = [
                'message' => 'No hay usuarios registrados',
                'status' => 404
            ];

            return response()->json($data, 404);
        }

        return response()->json($usuario, 200);
    }


    public function store(Request $request)
    {
        $validacion = Validator::make($request->all(), [
            'id_datos_personales' => 'required',
            'contrasenia' => 'required',
            'activar_usuario' => 'required',
            'clave_idioma' => 'required',
            'clave_activacion' => 'required',
            'llave_reinicio' => 'required',
            'hora_reinicio' => 'required'
        ]);

        if ($validacion->fails()) {
            $data = [
                'message' => 'Error en la validacion de los datos',
                'errors' => $validacion->errors(),
                'status' => 400
            ];

            return response()->json($data, 400);
        }

        $usuario = Usuario::create([
            'id_datos_personales' => $request->id_datos_personales,
            'contrasenia' => Hash::make($request->contrasenia),
            'activar_usuario' => $request->activar_usuario,
            'clave_idioma' => $request->clave_idioma,
            'clave_activacion' => $request->clave_activacion,
            'llave_reinicio' => $request->llave_reinicio,
            'hora_reinicio' => $request->hora_reinicio
        ]);

        if (!$usuario) {

            $data = [
                'message' => 'Error al crear el usuario',
                'status' => 500
            ];

            return response()->json($data, 500);
        }

        $data = [
            'message' => 'Usuario creado exitosamente',
            'usuario' => $usuario,
            'status' => 201
        ];

        return response()->json($data, 201);
    }

    public function show($id)
    {
        $usuario = Usuario::find($id);
        if (!$usuario) {
            $data = [
                'message' => 'No se encontro el usuario',
                'status' => 404
            ];

            return response()->json($data, 404);
        }

        $data = [

            'usuario' => $usuario,
            'status' => 200
        ];

        return response()->json($data, 200);
    }

    public function destroy($id)
    {
        $usuario = Usuario::find($id);
        if (!$usuario) {
            $data = [
                'message' => 'No se encontro el usuario',
                'status' => 404
            ];

            return response()->json($data, 404);
        }
        // Borra primero las autorizaciones asociadas
        DB::table('autorizacion_usuario')
            ->where('id_sistema_usuario', $id)
            ->delete();
        $usuario->delete();



        $data = [
            'message' => 'Usuario eliminado exitosamente',
            'status' => 200
        ];

        return response()->json($data, 200);
    }

    public function update(Request $request, $id)
    {
        $usuario = Usuario::find($id);
        if (!$usuario) {
            $data = [
                'message' => 'No se encontro el usuario',
                'status' => 404
            ];

            return response()->json($data, 404);
        }

        $validacion = Validator::make($request->all(), [
            'id_datos_personales' => 'required',
            'contrasenia' => 'required',
            'llave_reinicio' => 'required',
            'hora_reinicio' => 'required',
            'activar_usuario' => 'required',
            'clave_idioma' => 'required',
            'clave_activacion' => 'required'
        ]);

        if ($validacion->fails()) {
            $data = [
                'message' => 'Error en la validacion de los datos',
                'errors' => $validacion->errors(),
                'status' => 400
            ];

            return response()->json($data, 400);
        }

        $usuario->id_datos_personales = $request->id_datos_personales;
        if ($request->filled('contrasenia')) {
            $usuario->contrasenia = Hash::make(
                $request->contrasenia
            );
        }
        if ($request->filled('llave_reinicio')) {
            $usuario->llave_reinicio = $request->llave_reinicio;
        }
        if ($request->filled('hora_reinicio')) {
            $usuario->hora_reinicio = $request->hora_reinicio;
        }
        $usuario->activar_usuario = $request->activar_usuario;
        $usuario->clave_idioma = $request->clave_idioma;
        $usuario->clave_activacion = $request->clave_activacion;
        $usuario->save();

        $data = [
            'message' => 'Usuario actualizado con exito',
            'usuario' => $usuario,
            'status' => 200
        ];

        return response()->json($data, 200);
    }
}
