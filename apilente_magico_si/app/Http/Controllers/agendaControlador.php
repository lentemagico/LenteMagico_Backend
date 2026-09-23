<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\agendaConsultaModelo as AgendaConsulta;
use Illuminate\Support\Facades\Validator;

class agendaControlador extends Controller
{
    //
    public function index()
    {
    $agenda_consulta = AgendaConsulta::all();

    if ($agenda_consulta->isEmpty()) {
        $data = [
            'message' => 'No hay agendas registradas',
            'status' => 404
        ];

        return response()->json($data, 404);
    }

    return response()->json($agenda_consulta, 200);
}

     function store(Request $request)
    {
    $validacion = Validator::make($request->all(), [
        'id_cliente' => 'required',
        'fecha_hora' => 'required|date',
        'motivo' => 'required|string',
        'estado' => 'required|string'
    ]);

    if ($validacion->fails()) {
        $data = [
            'message' => 'Error en la validacion de los datos',
            'errors' => $validacion->errors(),
            'status' => 400
        ];

        return response()->json($data, 400);
    }

    $agenda_consulta = AgendaConsulta::create([
        'id_cliente' => $request->id_cliente,
        'fecha_hora' => $request->fecha_hora,
        'motivo' => $request->motivo,
        'estado' => $request->estado
    ]);

    if (!$agenda_consulta) {
        $data = [
            'message' => 'Error al crear la agenda',
            'status' => 500
        ];

        return response()->json($data, 500);
    }
    $data = [
        'message' => 'Agenda creada exitosamente',
        'agenda_consulta' => $agenda_consulta,
        'status' => 201
    ];

    return response()->json($agenda_consulta, 201);
}


public function show($id_agenda)
{
    $agenda_consulta = AgendaConsulta::find($id_agenda);

    if (!$agenda_consulta) {
        $data = [
            'message' => 'Agenda no encontrada',
            'status' => 404
        ];

        return response()->json($data, 404);
    }

    $data =[
        'agenda_consulta' => $agenda_consulta,
        'status' => 200
    ];

    return response()->json($data, 200);
}

public function destroy($id_agenda)
{
    $agenda_consulta = AgendaConsulta::find($id_agenda);

    if (!$agenda_consulta) {
        $data = [
            'message' => 'Agenda no encontrada',
            'status' => 404
        ];

        return response()->json($data, 404);
    }

    $agenda_consulta->delete();

    $data = [
        'message' => 'Agenda eliminada correctamente',
        'status' => 200
    ];

    return response()->json($data, 200);
}

public function update(Request $request, $id_agenda)
{
    $agenda_consulta = AgendaConsulta::find($id_agenda);

    if (!$agenda_consulta) {
        $data = [
            'message' => 'Agenda no encontrada',
            'status' => 404
        ];

        return response()->json($data, 404);
    }

    $validacion = Validator::make($request->all(), [
        'id_cliente' => 'required',
        'fecha_hora' => 'required|date',
        'motivo' => 'required|string',
        'estado' => 'required|string'
    ]);

    if ($validacion->fails()) {
        $data = [
            'message' => 'Error en la validacion de los datos',
            'errors' => $validacion->errors(),
            'status' => 400
        ];

        return response()->json($data, 400);
    }

    $agenda_consulta->id_agenda = $request->id_agenda;
    $agenda_consulta->id_cliente = $request->id_cliente;
    $agenda_consulta->fecha_hora = $request->fecha_hora;
    $agenda_consulta->motivo = $request->motivo;
    $agenda_consulta->estado = $request->estado;
    $agenda_consulta->save();

    $data = [
        'message' => 'Agenda actualizada correctamente',
        'agenda_consulta' => $agenda_consulta,
        'status' => 200
    ];

    return response()->json($data, 200);
}
}