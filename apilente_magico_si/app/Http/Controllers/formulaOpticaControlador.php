<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\formulaOpticaModelo as FormulaOptica;
use Illuminate\Support\Facades\Validator;

class formulaOpticaControlador extends Controller
{
    public function index()
    {
        $formula_optica = FormulaOptica::all();

        if ($formula_optica->isEmpty()) {
            $data = [
                'message' => 'No hay formulas opticas registradas',
                'status' => 404
            ];

            return response()->json($data, 404);
        }

        return response()->json($formula_optica, 200);
    }


    // Permite enviar datos o crear registros
    public function store(Request $request)
    {
        $validacion = Validator::make($request->all(), [
            'id_consulta' => 'required',
            'id_cliente' => 'required',
            'esfera_ojo_derecho_e_izquierdo' => 'required',
            'cilindro_ojo_derecho_e_izquierdo' => 'required',
            'eje_ojo_derecho_e_izquierdo' => 'required',
            'adicion' => 'required',
            'tipo_lente' => 'required',
            'uso' => 'required',
            'observaciones' => 'required',
            'fecha_generacion' => 'required'
        ]);

        if ($validacion->fails()) {
            $data = [
                'message' => 'Error en la validacion de los datos',
                'errors' => $validacion->errors(),
                'status' => 400
            ];

            return response()->json($data, 400);
        }

        $formula_optica = FormulaOptica::create([
            'id_consulta' => $request->id_consulta,
            'id_cliente' => $request->id_cliente,
            'esfera_ojo_derecho_e_izquierdo' => $request->esfera_ojo_derecho_e_izquierdo,
            'cilindro_ojo_derecho_e_izquierdo' => $request->cilindro_ojo_derecho_e_izquierdo,
            'eje_ojo_derecho_e_izquierdo' => $request->eje_ojo_derecho_e_izquierdo,
            'adicion' => $request->adicion,
            'tipo_lente' => $request->tipo_lente,
            'uso' => $request->uso,
            'observaciones' => $request->observaciones,
            'fecha_generacion' => $request->fecha_generacion
        ]);

        if (!$formula_optica) {
            $data = [
                'message' => 'Error al crear la formula optica',
                'status' => 500
            ];

            return response()->json($data, 500);
        }

        $data = [
            'message' => 'Formula optica creada correctamente',
            'formula_optica' => $formula_optica,
            'status' => 201
        ];

        return response()->json($data, 201);
    }


    // Mostrar un registro en especifico
    public function show($id)
    {
        $formula_optica = FormulaOptica::find($id);

        if (!$formula_optica) {
            $data = [
                'message' => 'Formula optica no encontrada',
                'status' => 404
            ];

            return response()->json($data, 404);
        }

        $data = [
            'formula_optica' => $formula_optica,
            'status' => 200
        ];

        return response()->json($data, 200);
    }


    // Eliminar un registro en especifico
    public function destroy($id)
    {
        $formula_optica = FormulaOptica::find($id);

        if (!$formula_optica) {
            $data = [
                'message' => 'Formula optica no encontrada',
                'status' => 404
            ];

            return response()->json($data, 404);
        }

        $formula_optica->delete();

        $data = [
            'message' => 'Formula optica eliminada correctamente',
            'status' => 200
        ];

        return response()->json($data, 200);
    }


    // Actualizar un registro en especifico
    public function update(Request $request, $id)
    {
        $formula_optica = FormulaOptica::find($id);

        if (!$formula_optica) {
            $data = [
                'message' => 'Formula optica no encontrada',
                'status' => 404
            ];

            return response()->json($data, 404);
        }

        $validacion = Validator::make($request->all(), [
            'id_consulta' => 'required',
            'id_cliente' => 'required',
            'esfera_ojo_derecho_e_izquierdo' => 'required',
            'cilindro_ojo_derecho_e_izquierdo' => 'required',
            'eje_ojo_derecho_e_izquierdo' => 'required',
            'adicion' => 'required',
            'tipo_lente' => 'required',
            'uso' => 'required',
            'observaciones' => 'required',
            'fecha_generacion' => 'required'
        ]);

        if ($validacion->fails()) {
            $data = [
                'message' => 'Error en la validacion de los datos',
                'errors' => $validacion->errors(),
                'status' => 400
            ];

            return response()->json($data, 400);
        }

        $formula_optica->id_consulta = $request->id_consulta;
        $formula_optica->id_cliente = $request->id_cliente;
        $formula_optica->esfera_ojo_derecho_e_izquierdo = $request->esfera_ojo_derecho_e_izquierdo;
        $formula_optica->cilindro_ojo_derecho_e_izquierdo = $request->cilindro_ojo_derecho_e_izquierdo;
        $formula_optica->eje_ojo_derecho_e_izquierdo = $request->eje_ojo_derecho_e_izquierdo;
        $formula_optica->adicion = $request->adicion;
        $formula_optica->tipo_lente = $request->tipo_lente;
        $formula_optica->uso = $request->uso;
        $formula_optica->observaciones = $request->observaciones;
        $formula_optica->fecha_generacion = $request->fecha_generacion;

        $formula_optica->save();

        $data = [
            'message' => 'Formula optica actualizada correctamente',
            'formula_optica' => $formula_optica,
            'status' => 200
        ];

        return response()->json($data, 200);
    }
}