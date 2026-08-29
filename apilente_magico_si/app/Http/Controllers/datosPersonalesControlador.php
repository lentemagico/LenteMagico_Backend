<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\datosPersonalesModelo as DatosPersonales;
use Illuminate\Support\Facades\Validator;

class datosPersonalesControlador extends Controller
{
    public function index()
    {
        $datos_personales = DatosPersonales::all();

        // Verifica si no hay registros
        if ($datos_personales->isEmpty()) {
            $data = [
                'message' => 'No hay datos personales registrados',
                'status' => 404
            ];

            return response()->json($data, 404);
        }

        return response()->json($datos_personales, 200);
    }

   // Permite enviar datos o crear registros
    public function store(Request $request)
    {
        $validacion = Validator::make($request->all(), [
            'id_tipo_documento' => 'required',
            'numero_documento' => 'required',
            'primer_nombre' => 'required',
            'segundo_nombre' => 'required',
            'primer_apellido' => 'required',
            'segundo_apellido' => 'required',
            'fecha_nacimiento' => 'required',
            'genero' => 'required',
            'correo' => 'required',
            'telefono' => 'required'
        ]);

        if ($validacion->fails()) {
            $data = [
                'message' => 'Error en la validacion de los datos',
                'errors' => $validacion->errors(),
                'status' => 400
            ];

            return response()->json($data, 400);
        }

        $datos_personales = DatosPersonales::create([
            'id_tipo_documento' => $request->id_tipo_documento,
            'numero_documento' => $request->numero_documento,
            'primer_nombre' => $request->primer_nombre,
            'segundo_nombre' => $request->segundo_nombre,
            'primer_apellido' => $request->primer_apellido,
            'segundo_apellido' => $request->segundo_apellido,
            'fecha_nacimiento' => $request->fecha_nacimiento,
            'genero' => $request->genero,
            'correo' => $request->correo,
            'telefono' => $request->telefono
        ]);

        if (!$datos_personales) {
            $data = [
                'message' => 'Error al crear los datos personales',
                'status' => 500
            ];

            return response()->json($data, 500);
        }

        $data = [
            'message' => 'Datos personales creados correctamente',
            'datos_personales' => $datos_personales,
            'status' => 201
        ];

        return response()->json($data, 201);
    }


    // Mostrar un registro en especifico
    public function show($id)
    {
        $datos_personales = DatosPersonales::find($id);

        if (!$datos_personales) {
            $data = [
                'message' => 'Datos personales no encontrados',
                'status' => 404
            ];

            return response()->json($data, 404);
        }

        $data = [
            'datos_personales' => $datos_personales,
            'status' => 200
        ];

        return response()->json($data, 200);
    }


    // Eliminar un registro en especifico
    public function destroy($id)
    {
        $datos_personales = DatosPersonales::find($id);

        if (!$datos_personales) {
            $data = [
                'message' => 'Datos personales no encontrados',
                'status' => 404
            ];

            return response()->json($data, 404);
        }

        $datos_personales->delete();

        $data = [
            'message' => 'Datos personales eliminados correctamente',
            'status' => 200
        ];

        return response()->json($data, 200);
    }


    // Actualizar un registro en especifico
    public function update(Request $request, $id)
    {
        $datos_personales = DatosPersonales::find($id);

        if (!$datos_personales) {
            $data = [
                'message' => 'Datos personales no encontrados',
                'status' => 404
            ];

            return response()->json($data, 404);
        }

        $validacion = Validator::make($request->all(), [
            'id_tipo_documento' => 'required',
            'numero_documento' => 'required',
            'primer_nombre' => 'required',
            'segundo_nombre' => 'required',
            'primer_apellido' => 'required',
            'segundo_apellido' => 'required',
            'fecha_nacimiento' => 'required',
            'genero' => 'required',
            'correo' => 'required',
            'telefono' => 'required'
        ]);

        if ($validacion->fails()) {
            $data = [
                'message' => 'Error en la validacion de los datos',
                'errors' => $validacion->errors(),
                'status' => 400
            ];

            return response()->json($data, 400);
        }

        $datos_personales->id_tipo_documento = $request->id_tipo_documento;
        $datos_personales->numero_documento = $request->numero_documento;
        $datos_personales->primer_nombre = $request->primer_nombre;
        $datos_personales->segundo_nombre = $request->segundo_nombre;
        $datos_personales->primer_apellido = $request->primer_apellido;
        $datos_personales->segundo_apellido = $request->segundo_apellido;
        $datos_personales->fecha_nacimiento = $request->fecha_nacimiento;
        $datos_personales->genero = $request->genero;
        $datos_personales->correo = $request->correo;
        $datos_personales->telefono = $request->telefono;

        $datos_personales->save();

        $data = [
            'message' => 'Datos personales actualizados correctamente',
            'datos_personales' => $datos_personales,
            'status' => 200
        ];

        return response()->json($data, 200);
    }
}