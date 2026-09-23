<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\tipoDocumentoModelo as TiposDocumento;
use Illuminate\Support\Facades\Validator;

class tipoDocumentoControlador extends Controller
{
    //
    public function index()
    {
        $tipo_documento = TiposDocumento::all();

        if ($tipo_documento->isEmpty()) {
            $data = [
                'message' => 'No hay tipos de documento registrados',
                'status' => 404
            ];

            return response()->json($data, 404);
        }

        return response()->json($tipo_documento, 200);
    }


    // Permite enviar datos o crear registros
    public function store(Request $request)
    {
        $validacion = Validator::make($request->all(), [
            'sigla' => 'required',
            'nombre_documento' => 'required',
            'estado' => 'required'
        ]);

        if ($validacion->fails()) {
            $data = [
                'message' => 'Error en la validacion de los datos',
                'errors' => $validacion->errors(),
                'status' => 400
            ];

            return response()->json($data, 400);
        }

        $tipo_documento = TiposDocumento::create([
            'sigla' => $request->sigla,
            'nombre_documento' => $request->nombre_documento,
            'estado' => $request->estado
        ]);

        if (!$tipo_documento) {
            $data = [
                'message' => 'Error al crear el tipo de documento',
                'status' => 500
            ];

            return response()->json($data, 500);
        }

        $data = [
            'message' => 'Tipo de documento creado correctamente',
            'tipo_documento' => $tipo_documento,
            'status' => 201
        ];

        return response()->json($data, 201);
    }


    // Mostrar un registro en especifico
    public function show($id)
    {
        $tipo_documento = TiposDocumento::find($id);

        if (!$tipo_documento) {
            $data = [
                'message' => 'Tipo de documento no encontrado',
                'status' => 404
            ];

            return response()->json($data, 404);
        }

        $data = [
            'tipo_documento' => $tipo_documento,
            'status' => 200
        ];

        return response()->json($data, 200);
    }


    // Eliminar un registro en especifico
    public function destroy($id)
    {
        $tipo_documento = TiposDocumento::find($id);

        if (!$tipo_documento) {
            $data = [
                'message' => 'Tipo de documento no encontrado',
                'status' => 404
            ];

            return response()->json($data, 404);
        }

        $tipo_documento->delete();

        $data = [
            'message' => 'Tipo de documento eliminado correctamente',
            'status' => 200
        ];

        return response()->json($data, 200);
    }


    // Actualizar un registro en especifico
    public function update(Request $request, $id)
    {
        $tipo_documento = TiposDocumento::find($id);

        if (!$tipo_documento) {
            $data = [
                'message' => 'Tipo de documento no encontrado',
                'status' => 404
            ];

            return response()->json($data, 404);
        }

        $validacion = Validator::make($request->all(), [
            'sigla' => 'required',
            'nombre_documento' => 'required',
            'estado' => 'required'
        ]);

        if ($validacion->fails()) {
            $data = [
                'message' => 'Error en la validacion de los datos',
                'errors' => $validacion->errors(),
                'status' => 400
            ];

            return response()->json($data, 400);
        }

        $tipo_documento->sigla = $request->sigla;
        $tipo_documento->nombre_documento = $request->nombre_documento;
        $tipo_documento->estado = $request->estado;

        $tipo_documento->save();

        $data = [
            'message' => 'Tipo de documento actualizado correctamente',
            'tipo_documento' => $tipo_documento,
            'status' => 200
        ];

        return response()->json($data, 200);
    }
}

