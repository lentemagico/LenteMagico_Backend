<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\antecedentesModelo as Antecedentes;
use Illuminate\Support\Facades\Validator;

class antecedentesControlador extends Controller
{
    //
    public function index()
    {
        $antecedentes = Antecedentes::all();

        if ($antecedentes->isEmpty()) {
            $data = [
                'message' => 'No hay antecedentes registrados',
                'status' => 404
            ];

            return response()->json($data, 404);
        }

        return response()->json($antecedentes, 200);
    }


    // Permite enviar datos o crear registros
    public function store(Request $request)
    {
        $validacion = Validator::make($request->all(), [
            'id_historia' => 'required',
            'antecedentes' => 'required'
        ]);

        if ($validacion->fails()) {
            $data = [
                'message' => 'Error en la validacion de los datos',
                'errors' => $validacion->errors(),
                'status' => 400
            ];

            return response()->json($data, 400);
        }

        $antecedentes = Antecedentes::create([
            'id_historia' => $request->id_historia,
            'antecedentes' => $request->antecedentes
        ]);

        if (!$antecedentes) {
            $data = [
                'message' => 'Error al crear los antecedentes',
                'status' => 500
            ];

            return response()->json($data, 500);
        }

        $data = [
            'message' => 'Antecedentes creados correctamente',
            'antecedentes' => $antecedentes,
            'status' => 201
        ];

        return response()->json($data, 201);
    }


    // Mostrar un registro en especifico
    public function show($id)
    {
        $antecedentes = Antecedentes::find($id);

        if (!$antecedentes) {
            $data = [
                'message' => 'Antecedentes no encontrados',
                'status' => 404
            ];

            return response()->json($data, 404);
        }

        $data = [
            'antecedentes' => $antecedentes,
            'status' => 200
        ];

        return response()->json($data, 200);
    }


    // Eliminar un registro en especifico
    public function destroy($id)
    {
        $antecedentes = Antecedentes::find($id);

        if (!$antecedentes) {
            $data = [
                'message' => 'Antecedentes no encontrados',
                'status' => 404
            ];

            return response()->json($data, 404);
        }

        $antecedentes->delete();

        $data = [
            'message' => 'Antecedentes eliminados correctamente',
            'status' => 200
        ];

        return response()->json($data, 200);
    }


    // Actualizar un registro en especifico
    public function update(Request $request, $id)
    {
        $antecedentes = Antecedentes::find($id);

        if (!$antecedentes) {
            $data = [
                'message' => 'Antecedentes no encontrados',
                'status' => 404
            ];

            return response()->json($data, 404);
        }

        $validacion = Validator::make($request->all(), [
            'id_historia' => 'required',
            'antecedentes' => 'required'
        ]);

        if ($validacion->fails()) {
            $data = [
                'message' => 'Error en la validacion de los datos',
                'errors' => $validacion->errors(),
                'status' => 400
            ];

            return response()->json($data, 400);
        }

        $antecedentes->id_historia = $request->id_historia;
        $antecedentes->antecedentes = $request->antecedentes;

        $antecedentes->save();

        $data = [
            'message' => 'Antecedentes actualizados correctamente',
            'antecedentes' => $antecedentes,
            'status' => 200
        ];

        return response()->json($data, 200);
    }
}
