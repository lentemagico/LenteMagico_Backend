<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\AgendaConsultaModelo;
use Illuminate\Support\Facades\Validator;

class AgendaConsultaControlador extends Controller
{
    public function index()
    {
        $agendaConsulta = AgendaConsultaModelo::all();

        if ($agendaConsulta->isEmpty()) {
            $data = [
                'message' => 'No hay consultas agendadas',
                'status' => 404
            ];
            return response()->json($data, 404);
        }

        return response()->json($agendaConsulta, 200);
    }

    public function store(Request $request)
    {
        $validacion = Validator::make($request->all(), [
            'fecha_hora' => 'required',
            'id_cliente' => 'required',
            'motivo' => 'required',
            'estado' => 'required'
        ]);

        if ($validacion->fails()) {
            $data = [
                'message' => 'Error al validar la consulta agendada',
                'errors' => $validacion->errors(),
                'status' => 400
            ];
            return response()->json($data, 400);
        }

        $agendaConsulta = AgendaConsultaModelo::create([
            'fecha_hora' => $request->fecha_hora,
            'id_cliente' => $request->id_cliente,
            'motivo' => $request->motivo,
            'estado' => $request->estado
        ]);

        if (!$agendaConsulta) {
            $data = [
                'message' => 'Error al crear la consulta agendada',
                'status' => 500
            ];
            return response()->json($data, 500);
        }

        $data = [
            'message' => 'Consulta creada correctamente',
            'agendarConsulta' => $agendaConsulta,
            'status' => 201
        ];
        return response()->json($data, 201);
    }

    public function show($id_agenda)
    {
        $agendaConsulta = AgendaConsultaModelo::find($id_agenda);
        if (!$agendaConsulta) {
            $data = [
                'message' => 'Error, consulta no encontrada',
                'status' => 404
            ];

            return response()->json($data, 404);
        }

        $data = [
            'agendaConsulta' => $agendaConsulta,
            'status' => 200
        ];

        return response()->json($data, 200);
    }

    public function destroy($id_agenda)
    {
        $agendaConsulta = AgendaConsultaModelo::find($id_agenda);
        if (!$agendaConsulta) {
            $data = [
                'message' => 'Consulta no encontrada',
                'status' => 404 // verifica si la ruta existe
            ];

            return response()->json($data, 404);
        }

        $agendaConsulta->delete();

        $data = [
            'message' => 'Consulta eliminada correctamente',
            'status' => 200 // solicitud exitosa
        ];

        return response()->json($data, 200);
    }

    public function update(Request $request, $id_agenda)
    {
        $agendaConsulta = AgendaConsultaModelo::find($id_agenda);
        if (!$agendaConsulta) {
            $data = [
                'message' => 'Consulta no encontrada',
                'status' => 404 // verifica si la ruta existe
            ];

            return response()->json($data, 404);
        }

        $validacion = Validator::make($request->all(), [
            'fecha_hora' => 'required',
            'id_cliente' => 'required',
            'motivo' => 'required',
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

        $agendaConsulta->fecha_hora = $request->fecha_hora;
        $agendaConsulta->id_cliente = $request->id_cliente;
        $agendaConsulta->motivo = $request->motivo;
        $agendaConsulta->estado = $request->estado;
        $agendaConsulta->save();

        $data = [
            'message' => 'Consulta actualizada correctamente',
            'agendaConsulta' => $agendaConsulta,
            'status' => 200 // solicitud exitosa
        ];

        return response()->json($data, 200);
    }
}
