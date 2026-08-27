<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\pagoModelo;
use Illuminate\Support\Facades\Validator;

class pagoControlador extends Controller
{
    public function index()
    {
        $pago = pagoModelo::all();

        if ($pago->isEmpty()) {
            $data = [
                'message' => 'No hay pagos registrados',
                'status' => 404
            ];
            return response()->json($data, 404);
        }

        return response()->json($pago, 200);
    }

    public function store(Request $request)
    {
        $validacion = Validator::make($request->all(), [
            'id_venta' => 'required',
            'fecha_pago' => 'required',
            'monto' => 'required',
            'metodo_pago' => 'required',
            'monto_recibido' => 'required',
            'cambio' => 'required'
        ]);

        if ($validacion->fails()) {
            $data = [
                'message' => 'Error al validar el pago',
                'errors' => $validacion->errors(),
                'status' => 400
            ];
            return response()->json($data, 400);
        }

        $pago = pagoModelo::create([
            'id_venta' => $request->id_venta,
            'fecha_pago' => $request->fecha_pago,
            'monto' => $request->monto,
            'metodo_pago' => $request->metodo_pago,
            'monto_recibido' => $request->monto_recibido,
            'cambio' => $request->cambio
        ]);

        if (!$pago) {
            $data = [
                'message' => 'Error al crear el pago',
                'status' => 500
            ];
            return response()->json($data, 500);
        }

        $data = [
            'message' => 'Pago creado correctamente',
            'pago' => $pago,
            'status' => 201
        ];
        return response()->json($data, 201);
    }

    public function show($id_pago)
    {
        $pago = pagoModelo::find($id_pago);
        if (!$pago) {
            $data = [
                'message' => 'Error, pago no encontrado',
                'status' => 404
            ];

            return response()->json($data, 404);
        }

        $data = [
            'pago' => $pago,
            'status' => 200
        ];

        return response()->json($data, 200);
    }

    public function destroy($id_pago)
    {
        $pago = pagoModelo::find($id_pago);
        if (!$pago) {
            $data = [
                'message' => 'Pago no encontrado',
                'status' => 404 // verifica si la ruta existe
            ];

            return response()->json($data, 404);
        }

        $pago->delete();

        $data = [
            'message' => 'Pago eliminado correctamente',
            'status' => 200 // solicitud exitosa
        ];

        return response()->json($data, 200);
    }

    public function update(Request $request, $id_pago)
    {
        $pago = pagoModelo::find($id_pago);
        if (!$pago) {
            $data = [
                'message' => 'Pago no encontrado',
                'status' => 404 // verifica si la ruta existe
            ];

            return response()->json($data, 404);
        }

        $validacion = Validator::make($request->all(), [
            'id_venta' => 'required',
            'fecha_pago' => 'required',
            'monto' => 'required',
            'metodo_pago' => 'required',
            'monto_recibido' => 'required',
            'cambio' => 'required'
        ]);

        if ($validacion->fails()) {
            $data = [
                'message' => 'Error en la validacion de los datos',
                'errors' => $validacion->errors(),
                'status' => 400 // verifica si la ruta existe
            ];

            return response()->json($data, 400);
        }

        $pago->id_venta = $request->id_venta;
        $pago->fecha_pago = $request->fecha_pago;
        $pago->monto = $request->monto;
        $pago->metodo_pago = $request->metodo_pago;
        $pago->monto_recibido = $request->monto_recibido;
        $pago->cambio = $request->cambio;
        $pago->save();

        $data = [
            'message' => 'Pago actualizado correctamente',
            'pago' => $pago,
            'status' => 200 // solicitud exitosa
        ];

        return response()->json($data, 200);
    }
}
