<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\facturaModelo;
use Illuminate\Support\Facades\Validator;

class facturaControlador extends Controller
{
    public function index()
    {
        $factura = facturaModelo::all();

        if ($factura->isEmpty()) {
            $data = [
                'message' => 'No hay facturas registradas',
                'status' => 404
            ];
            return response()->json($data, 404);
        }

        return response()->json($factura, 200);
    }

    public function store(Request $request)
    {
        $validacion = Validator::make($request->all(), [
            'fecha_registro' => 'required',
            'id_datos_personales' => 'required'
        ]);

        if ($validacion->fails()) {
            $data = [
                'message' => 'Error al validar la factura',
                'errors' => $validacion->errors(),
                'status' => 400
            ];
            return response()->json($data, 400);
        }

        $factura = facturaModelo::create([
            'fecha_emision' => $request->fecha_emision,
            'id_pago' => $request->id_pago,
            'num_factura' => $request->num_factura
        ]);

        if (!$factura) {
            $data = [
                'message' => 'Error al crear la factura',
                'status' => 500
            ];
            return response()->json($data, 500);
        }

        $data = [
            'message' => 'Factura creada correctamente',
            'factura' => $factura,
            'status' => 201
        ];
        return response()->json($data, 201);
    }

    public function show($id_factura)
    {
        $factura = facturaModelo::find($id_factura);
        if (!$factura) {
            $data = [
                'message' => 'Error, factura no encontrada',
                'status' => 404
            ];

            return response()->json($data, 404);
        }

        $data = [
            'factura' => $factura,
            'status' => 200
        ];

        return response()->json($data, 200);
    }

    public function destroy($id_factura)
    {
        $factura = facturaModelo::find($id_factura);
        if (!$factura) {
            $data = [
                'message' => 'Factura no encontrada',
                'status' => 404 // verifica si la ruta existe
            ];

            return response()->json($data, 404);
        }

        $factura->delete();

        $data = [
            'message' => 'Factura eliminada correctamente',
            'status' => 200 // solicitud exitosa
        ];

        return response()->json($data, 200);
    }

    public function update(Request $request, $id_factura)
    {
        $factura = facturaModelo::find($id_factura);
        if (!$factura) {
            $data = [
                'message' => 'Factura no encontrada',
                'status' => 404 // verifica si la ruta existe
            ];

            return response()->json($data, 404);
        }

        $validacion = Validator::make($request->all(), [
            'fecha_emision' => 'required',
            'id_pago' => 'required',
            'num_factura' => 'required'
        ]);

        if ($validacion->fails()) {
            $data = [
                'message' => 'Error en la validacion de los datos',
                'errors' => $validacion->errors(),
                'status' => 400 // verifica si la ruta existe
            ];

            return response()->json($data, 400);
        }

        $factura->fecha_emision = $request->fecha_emision;
        $factura->id_pago = $request->id_pago;
        $factura->num_factura = $request->num_factura;
        $factura->save();

        $data = [
            'message' => 'Factura actualizada correctamente',
            'factura' => $factura,
            'status' => 200 // solicitud exitosa
        ];

        return response()->json($data, 200);
    }
}
