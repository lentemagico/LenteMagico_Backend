<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\autorizacionModelo as Autorizacion;
use Illuminate\Support\Facades\Validator;
//use App\Http\Controllers\autorizacionControlador;

//use App\Http\Controllers\Controller;

class autorizacionControlador extends Controller
{
     // Funcion Listar: trae todos los datos de la tabla

    public function index()
    {
        $autorizacion = Autorizacion::all();

        // Verifica si no hay registros
        if ($autorizacion->isEmpty()) {
            $data = [
                'message' => 'No hay autorizaciones registradas',
                'status' => 404
            ];

            return response()->json($data, 404);
        }

        return response()->json($autorizacion, 200);
    }

    // Permite enviar datos o crear registros
    public function store(Request $request)
    {
        $validacion = Validator::make($request->all(), [
            'id' => 'required',
            'nombre' => 'required'
        ]);

        if ($validacion->fails()) {
            $data = [
                'message' => 'Error en la validacion de los datos',
                'errors' => $validacion->errors(),
                'status' => 400
            ];

            return response()->json($data, 400);
        }

        $autorizacion = autorizacion::create([
            'nombre' => $request->nombre
        ]);

        if (!$autorizacion) {
            $data = [
                'message' => 'Error al crear la autorizacion',
                'status' => 500
            ];

            return response()->json($data, 500);
        }

        $data = [
            'message' => 'Autorizacion creada correctamente',
            'autorizacion' => $autorizacion,
            'status' => 201
        ];

        return response()->json($data, 201);
    }

    // clase de hoy 21/08/2026, realizamos la funcion show para mostrar un registro en especifico, y la funcion destroy para eliminar un registro en especifico 
    // y tambien la funcion update para actualizar un registro en especifico, todas estas funciones reciben un parametro $id que es el id del registro que se quiere mostrar, eliminar o actualizar

    public function show($id){
    $autorizacion = Autorizacion::find($id);
    if (!$autorizacion) {
        $data = [
            'message' => 'Autorizacion no encontrada',
            'status' => 404 // verifica si la ruta existe 
        ];

        return response()->json($data, 404);
    }

    $data =[
        'autorizacion' => $autorizacion,
        'status' => 200 // solicitud exitosa
    ];
    return response()->json($data, 200);
}

public function destroy($id){
    $autorizacion = Autorizacion::find($id);
    if (!$autorizacion) {
        $data = [
            'message' => 'Autorizacion no encontrada',
            'status' => 404 // verifica si la ruta existe
        ];

        return response()->json($data, 404);
    }

    $autorizacion->delete();

    $data = [
        'message' => 'Autorizacion eliminada correctamente',
        'status' => 200 // solicitud exitosa
    ];

    return response()->json($data, 200);
}

public function update(Request $request, $id){
    $autorizacion = Autorizacion::find($id);
    if (!$autorizacion) {
        $data = [
            'message' => 'Autorizacion no encontrada',
            'status' => 404 // verifica si la ruta existe
        ];

        return response()->json($data, 404);
    }

    $validacion = Validator::make($request->all(), [
        'id' => 'required',
        'nombre' => 'required'
    ]);

    if ($validacion->fails()) {
        $data = [
            'message' => 'Error en la validacion de los datos',
            'errors' => $validacion->errors(),
            'status' => 400 // verifica si la ruta existe
        ];

        return response()->json($data, 400);
    }

    $autorizacion->id = $request->id;
    $autorizacion->nombre = $request->nombre;
    $autorizacion->save();

    $data = [
        'message' => 'Autorizacion actualizada correctamente',
        'autorizacion' => $autorizacion,
        'status' => 200 // solicitud exitosa
    ];

    return response()->json($data, 200);

}

}