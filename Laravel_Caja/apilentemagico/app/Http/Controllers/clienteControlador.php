<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\clienteModelo;
use Illuminate\Support\Facades\Validator;

class clienteControlador extends Controller
{
    public function index()
    {
        $cliente = clienteModelo::all();

        if ($cliente->isEmpty()) {
            $data = [
                'message' => 'No hay clientes registrados',
                'status' => 404
            ];
            return response()->json($data, 404);
        }

        return response()->json($cliente, 200);
    }

    public function store(Request $request)
    {
        $validacion = Validator::make($request->all(), [
            'fecha_registro' => 'required',
            'id_datos_personales' => 'required'
        ]);

        if ($validacion->fails()) {
            $data = [
                'message' => 'Error al validar el cliente',
                'errors' => $validacion->errors(),
                'status' => 400
            ];
            return response()->json($data, 400);
        }

        $cliente = clienteModelo::create([
            'fecha_registro' => $request->fecha_registro,
            'id_datos_personales' => $request->id_datos_personales
        ]);

        if (!$cliente) {
            $data = [
                'message' => 'Error al crear el cliente',
                'status' => 500
            ];
            return response()->json($data, 500);
        }

        $data = [
            'message' => 'Cliente creado correctamente',
            'cliente' => $cliente,
            'status' => 201
        ];
        return response()->json($data, 201);
    }

    public function show($id_cliente)
    {
        $cliente = clienteModelo::find($id_cliente);
        if (!$cliente) {
            $data = [
                'message' => 'Error, cliente no encontrado',
                'status' => 404
            ];

            return response()->json($data, 404);
        }

        $data = [
            'cliente' => $cliente,
            'status' => 200
        ];

        return response()->json($data, 200);
    }

    public function destroy($id_cliente)
    {
        $cliente = clienteModelo::find($id_cliente);
        if (!$cliente) {
            $data = [
                'message' => 'Cliente no encontrado',
                'status' => 404 // verifica si la ruta existe
            ];

            return response()->json($data, 404);
        }

        $cliente->delete();

        $data = [
            'message' => 'Cliente eliminado correctamente',
            'status' => 200 // solicitud exitosa
        ];

        return response()->json($data, 200);
    }

    public function update(Request $request, $id_cliente)
    {
        $cliente = clienteModelo::find($id_cliente);
        if (!$cliente) {
            $data = [
                'message' => 'Cliente no encontrado',
                'status' => 404 // verifica si la ruta existe
            ];

            return response()->json($data, 404);
        }

        $validacion = Validator::make($request->all(), [
            'fecha_registro' => 'required',
            'id_datos_personales' => 'required'
        ]);

        if ($validacion->fails()) {
            $data = [
                'message' => 'Error en la validacion de los datos',
                'errors' => $validacion->errors(),
                'status' => 400 // verifica si la ruta existe
            ];

            return response()->json($data, 400);
        }

        $cliente->fecha_registro = $request->fecha_registro;
        $cliente->id_datos_personales = $request->id_datos_personales;
        $cliente->save();

        $data = [
            'message' => 'Cliente actualizado correctamente',
            'cliente' => $cliente,
            'status' => 200 // solicitud exitosa
        ];

        return response()->json($data, 200);
    }
}
