<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\ventaModelo;
use Illuminate\Support\Facades\Validator;

class ventaControlador extends Controller
{
    public function index()
    {
        $venta = ventaModelo::all();

        if ($venta->isEmpty()) {
            $data = [
                'message' => 'No hay ventas registradas',
                'status' => 404
            ];
            return response()->json($data, 404);
        }

        return response()->json($venta, 200);
    }

    public function store(Request $request)
    {
        $validacion = Validator::make($request->all(), [
            'fecha_venta' => 'required',
            'id_usuario' => 'required',
            'id_cliente' => 'required',
            'estado' => 'required'
        ]);

        if ($validacion->fails()) {
            $data = [
                'message' => 'Error al validar la venta',
                'errors' => $validacion->errors(),
                'status' => 400
            ];
            return response()->json($data, 400);
        }

        $venta = ventaModelo::create([
            'fecha_venta' => $request->fecha_venta,
            'id_usuario' => $request->id_usuario,
            'id_cliente' => $request->id_cliente,
            'estado' => $request->estado
        ]);

        if (!$venta) {
            $data = [
                'message' => 'Error al crear la venta',
                'status' => 500
            ];
            return response()->json($data, 500);
        }

        $data = [
            'message' => 'Venta creada correctamente',
            'venta' => $venta,
            'status' => 201
        ];
        return response()->json($data, 201);
    }

    public function show($id_venta)
    {
        $venta = ventaModelo::find($id_venta);
        if (!$venta) {
            $data = [
                'message' => 'Error, venta no encontrada',
                'status' => 404
            ];

            return response()->json($data, 404);
        }

        $data = [
            'venta' => $venta,
            'status' => 200
        ];

        return response()->json($data, 200);
    }

    public function destroy($id_venta)
    {
        $venta = ventaModelo::find($id_venta);
        if (!$venta) {
            $data = [
                'message' => 'Venta no encontrada',
                'status' => 404 // verifica si la ruta existe
            ];

            return response()->json($data, 404);
        }

        $venta->delete();

        $data = [
            'message' => 'Venta eliminada correctamente',
            'status' => 200 // solicitud exitosa
        ];

        return response()->json($data, 200);
    }

    public function update(Request $request, $id_venta)
    {
        $venta = ventaModelo::find($id_venta);
        if (!$venta) {
            $data = [
                'message' => 'Venta no encontrada',
                'status' => 404 // verifica si la ruta existe
            ];

            return response()->json($data, 404);
        }

        $validacion = Validator::make($request->all(), [
            'fecha_venta' => 'required',
            'id_usuario' => 'required',
            'id_cliente' => 'required',
            'estado' => 'required'
        ]);

        if ($validacion->fails()) {
            $data = [
                'message' => 'Error en la validacion de los datos',
                'errors' => $validacion->errors(),
                'status' => 400 // verifica si la ruta existe
            ];

            return response()->json($data, 400);
        }

        $venta->fecha_venta = $request->fecha_venta;
        $venta->id_usuario = $request->id_usuario;
        $venta->id_cliente = $request->id_cliente;
        $venta->estado = $request->estado;
        $venta->save();

        $data = [
            'message' => 'Venta actualizada correctamente',
            'venta' => $venta,
            'status' => 200 // solicitud exitosa
        ];

        return response()->json($data, 200);
    }
}
