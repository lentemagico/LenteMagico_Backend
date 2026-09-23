<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\logErroresModelo as LogErrores;
use Illuminate\Support\Facades\Validator;

class logErroresControlador extends Controller
{
    //
     public function index()
    {
        $log_errores = LogErrores::all();

        if ($log_errores->isEmpty()) {
            $data = [
                'message' => 'No hay errores registrados',
                'status' => 404
            ];

            return response()->json($data, 404);
        }

        return response()->json($log_errores, 200);
    }


    // Permite enviar datos o crear registros
    public function store(Request $request)
    {
        $validacion = Validator::make($request->all(), [
            'id_usuario' => 'required',
            'nivel' => 'required',
            'nombre_usuario' => 'required',
            'mensaje' => 'required',
            'fecha' => 'required'
        ]);

        if ($validacion->fails()) {
            $data = [
                'message' => 'Error en la validacion de los datos',
                'errors' => $validacion->errors(),
                'status' => 400
            ];

            return response()->json($data, 400);
        }

        $log_errores = LogErrores::create([
            'id_usuario' => $request->id_usuario,
            'nivel' => $request->nivel,
            'nombre_usuario' => $request->nombre_usuario,
            'mensaje' => $request->mensaje,
            'fecha' => $request->fecha
        ]);

        if (!$log_errores) {
            $data = [
                'message' => 'Error al registrar el error',
                'status' => 500
            ];

            return response()->json($data, 500);
        }

        $data = [
            'message' => 'Error registrado correctamente',
            'log_errores' => $log_errores,
            'status' => 201
        ];

        return response()->json($data, 201);
    }


    // Mostrar un registro en especifico
    public function show($id)
    {
        $log_errores = LogErrores::find($id);

        if (!$log_errores) {
            $data = [
                'message' => 'Error no encontrado',
                'status' => 404
            ];

            return response()->json($data, 404);
        }

        $data = [
            'log_errores' => $log_errores,
            'status' => 200
        ];

        return response()->json($data, 200);
    }


    // Eliminar un registro en especifico
    public function destroy($id)
    {
        $log_errores = LogErrores::find($id);

        if (!$log_errores) {
            $data = [
                'message' => 'Error no encontrado',
                'status' => 404
            ];

            return response()->json($data, 404);
        }

        $log_errores->delete();

        $data = [
            'message' => 'Error eliminado correctamente',
            'status' => 200
        ];

        return response()->json($data, 200);
    }


    // Actualizar un registro en especifico
    public function update(Request $request, $id)
    {
        $log_errores = LogErrores::find($id);

        if (!$log_errores) {
            $data = [
                'message' => 'Error no encontrado',
                'status' => 404
            ];

            return response()->json($data, 404);
        }

        $validacion = Validator::make($request->all(), [
            'id_usuario' => 'required',
            'nivel' => 'required',
            'nombre_usuario' => 'required',
            'mensaje' => 'required',
            'fecha' => 'required'
        ]);

        if ($validacion->fails()) {
            $data = [
                'message' => 'Error en la validacion de los datos',
                'errors' => $validacion->errors(),
                'status' => 400
            ];

            return response()->json($data, 400);
        }

        $log_errores->id_usuario = $request->id_usuario;
        $log_errores->nivel = $request->nivel;
        $log_errores->nombre_usuario = $request->nombre_usuario;
        $log_errores->mensaje = $request->mensaje;
        $log_errores->fecha = $request->fecha;

        $log_errores->save();

        $data = [
            'message' => 'Error actualizado correctamente',
            'log_errores' => $log_errores,
            'status' => 200
        ];

        return response()->json($data, 200);
    }
}
