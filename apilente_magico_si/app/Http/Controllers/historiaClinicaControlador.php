<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\historiaClinicaModelo as HistoriaClinica;
use Illuminate\Support\Facades\Validator;

class historiaClinicaControlador extends Controller
{
    public function index()
    {
        $historia_clinica = HistoriaClinica::all();

        if ($historia_clinica->isEmpty()) {
            $data = [
                'message' => 'No hay historias clinicas registradas',
                'status' => 404
            ];

            return response()->json($data, 404);
        }

        return response()->json($historia_clinica, 200);
    }


    // Permite enviar datos o crear registros
    public function store(Request $request)
    {
        $validacion = Validator::make($request->all(), [
            'id_cliente' => 'required',
            'fecha_apertura' => 'required',
            'evolucion' => 'required',
            'num_consulta' => 'required'
        ]);

        if ($validacion->fails()) {
            $data = [
                'message' => 'Error en la validacion de los datos',
                'errors' => $validacion->errors(),
                'status' => 400
            ];

            return response()->json($data, 400);
        }

        $historia_clinica = HistoriaClinica::create([
            'id_cliente' => $request->id_cliente,
            'fecha_apertura' => $request->fecha_apertura,
            'evolucion' => $request->evolucion,
            'num_consulta' => $request->num_consulta
        ]);

        if (!$historia_clinica) {
            $data = [
                'message' => 'Error al crear la historia clinica',
                'status' => 500
            ];

            return response()->json($data, 500);
        }

        $data = [
            'message' => 'Historia clinica creada correctamente',
            'historia_clinica' => $historia_clinica,
            'status' => 201
        ];

        return response()->json($data, 201);
    }


    // Mostrar un registro en especifico
    public function show($id)
    {
        $historia_clinica = HistoriaClinica::find($id);

        if (!$historia_clinica) {
            $data = [
                'message' => 'Historia clinica no encontrada',
                'status' => 404
            ];

            return response()->json($data, 404);
        }

        $data = [
            'historia_clinica' => $historia_clinica,
            'status' => 200
        ];

        return response()->json($data, 200);
    }


    // Eliminar un registro en especifico
    public function destroy($id)
    {
        $historia_clinica = HistoriaClinica::find($id);

        if (!$historia_clinica) {
            $data = [
                'message' => 'Historia clinica no encontrada',
                'status' => 404
            ];

            return response()->json($data, 404);
        }

        $historia_clinica->delete();

        $data = [
            'message' => 'Historia clinica eliminada correctamente',
            'status' => 200
        ];

        return response()->json($data, 200);
    }


    // Actualizar un registro en especifico
    public function update(Request $request, $id)
    {
        $historia_clinica = HistoriaClinica::find($id);

        if (!$historia_clinica) {
            $data = [
                'message' => 'Historia clinica no encontrada',
                'status' => 404
            ];

            return response()->json($data, 404);
        }

        $validacion = Validator::make($request->all(), [
            'id_cliente' => 'required',
            'fecha_apertura' => 'required',
            'evolucion' => 'required',
            'num_consulta' => 'required'
        ]);

        if ($validacion->fails()) {
            $data = [
                'message' => 'Error en la validacion de los datos',
                'errors' => $validacion->errors(),
                'status' => 400
            ];

            return response()->json($data, 400);
        }

        $historia_clinica->id_cliente = $request->id_cliente;
        $historia_clinica->fecha_apertura = $request->fecha_apertura;
        $historia_clinica->evolucion = $request->evolucion;
        $historia_clinica->num_consulta = $request->num_consulta;

        $historia_clinica->save();

        $data = [
            'message' => 'Historia clinica actualizada correctamente',
            'historia_clinica' => $historia_clinica,
            'status' => 200
        ];

        return response()->json($data, 200);
    }
}