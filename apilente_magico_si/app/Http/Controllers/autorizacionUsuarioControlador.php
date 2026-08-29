<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\autorizacionUsuarioModelo as AutorizacionUsuario;
use Illuminate\Support\Facades\Validator;

class autorizacionUsuarioControlador extends Controller
{
    // Funcion Listar: trae todos los registros
    public function index()
    {
        $autorizacion_usuario = AutorizacionUsuario::all();

        if ($autorizacion_usuario->isEmpty()) {
            $data = [
                'message' => 'No hay autorizaciones de usuarios registradas',
                'status' => 404
            ];

            return response()->json($data, 404);
        }

        return response()->json($autorizacion_usuario, 200);
    }


    // Permite enviar datos o crear registros
    public function store(Request $request)
    {
        $validacion = Validator::make($request->all(), [
            'id_autorizacion' => 'required',
            'id_sistema_usuario' => 'required'
        ]);

        if ($validacion->fails()) {
            $data = [
                'message' => 'Error en la validacion de los datos',
                'errors' => $validacion->errors(),
                'status' => 400
            ];

            return response()->json($data, 400);
        }

          $autorizacion_usuario = AutorizacionUsuario::create([
            'id_autorizacion' => $request->id_autorizacion,
            'id_sistema_usuario' => $request->id_sistema_usuario
        ]);

        if (!$autorizacion_usuario) {
            $data = [
                'message' => 'Error al crear la autorizacion de usuario',
                'status' => 500
            ];
            return response()->json($data, 500);
        }

        $data = [
            'message' => 'Autorizacion de usuario creada exitosamente',
            'autorizacion_usuario' => $autorizacion_usuario,
            'status' => 201
        ];

        return response()->json($data, 201);

    }

    public function show($id_autorizacion, $id_sistema_usuario)
    {
        $autorizacion_usuario = AutorizacionUsuario::find($id_autorizacion, $id_sistema_usuario);
        if (! $autorizacion_usuario) {
            $data = [
                'message' => 'No se encontró la autorizacion de usuario',
                'status' => 404
            ];
            return response()->json($data, 404);
        }

        $data = [
          
            'autorizacion_usuario' => $autorizacion_usuario,
            'status' => 200
        ];
        return response()->json($data, 200);
    }
    public function destroy($id_autorizacion, $id_sistema_usuario)
    {
        $autorizacion_usuario = AutorizacionUsuario::find($id_autorizacion, $id_sistema_usuario);
        if (!$autorizacion_usuario) {
            $data = [
                'message' => 'No se encontró la autorizacion de usuario',
                'status' => 404
            ];
            return response()->json($data, 404);
        }

        $autorizacion_usuario->delete();

        $data = [
            'message' => 'Autorizacion de usuario eliminada exitosamente',
            'status' => 200
        ];
        return response()->json($data, 200);
    }
    public function update(Request $request, $id_autorizacion, $id_sistema_usuario)
    {
        $autorizacion_usuario = AutorizacionUsuario::find($id_autorizacion, $id_sistema_usuario);
        if (!$autorizacion_usuario) {
            $data = [
                'message' => 'No se encontró la autorizacion de usuario',
                'status' => 404
            ];
            return response()->json($data, 404);
        }
        $validacion = validator::make($request ->all(), [
            'id_autorizacion' => 'required',
            'id_sistema_usuario' => 'required'
        ]);
        
        if ($validacion->fails()) {
            $data = [
                'message' => 'Error en la validacion de los datos',
                'errors' => $validacion->errors(),
                'status' => 400
            ];
            return response()->json($data, 400);
        }
        $autorizacion_usuario->id_autorizacion=$request->id_autorizacion;
        $autorizacion_usuario->id_sistema_usuario=$request->id_sistema_usuario;
        $autorizacion_usuario->save();

        $data = [ 
            
            'message' => 'Autorizacion de usuario actualizada exitosamente',
            'autorizacion_usuario' => $autorizacion_usuario,
            'status' => 200

        ];
        return response()->json($data, 200);
    }

}