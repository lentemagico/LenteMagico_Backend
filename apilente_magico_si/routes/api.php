<?php


use Illuminate\Support\Facades\Route;
use App\Http\Controllers\autorizacionControlador;
use App\Http\Controllers\autorizacionUsuarioControlador;
use App\Http\Controllers\usuarioControlador;
use App\Http\Controllers\datosPersonalesControlador;
use App\Http\Controllers\tipoDocumentoControlador;
use App\Http\Controllers\logErroresControlador;
use App\Http\Controllers\agendaControlador;
use App\Http\Controllers\antecedentesControlador;
use App\Http\Controllers\historiaClinicaControlador;

use App\Http\Controllers\formulaOpticaControlador;
use Illuminate\Http\Request;

//Route::middleware('auth:sanctum') ->get('/user', function (Request $request) {
 //   return $request->user();
//)
//};

Route::get('/user', function (Request $request){
    return $request->user();
})->middleware('auth:sanctum');

Route::get('/autorizacion', [autorizacionControlador::class, 'index']);
Route::post('/autorizacion', [autorizacionControlador::class, 'store']);
Route::get('/autorizacion/{id}', [autorizacionControlador::class, 'show']);
Route::put('/autorizacion/{id}', [autorizacionControlador::class,   'update']); 
Route::delete('/autorizacion/{id}', [autorizacionControlador::class, 'destroy']);

Route::get('/autorizacion_usuario', [autorizacionUsuarioControlador::class, 'index']);
Route::post('/autorizacion_usuario', [autorizacionUsuarioControlador::class, 'store']);
Route::get('/autorizacion_usuario/{id_autorizacion}/{id_sistema_usuario}', [autorizacionUsuarioControlador::class, 'show']);
Route::put('/autorizacion_usuario/{id_autorizacion}/{id_sistema_usuario}', [autorizacionUsuarioControlador::class, 'update']);
Route::delete('/autorizacion_usuario/{id_autorizacion}/{id_sistema_usuario}', [autorizacionUsuarioControlador::class, 'destroy']);

Route::get('/usuario', [UsuarioControlador::class, 'index']);
Route::post('/usuario', [UsuarioControlador::class, 'store']);
Route::get('/usuario/{id}', [UsuarioControlador::class, 'show']);
Route::put('/usuario/{id}', [UsuarioControlador::class, 'update']);
Route::delete('/usuario/{id}', [UsuarioControlador::class, 'destroy']);

Route::get('/datos_personales', [datosPersonalesControlador::class, 'index']);
Route::post('/datos_personales', [datosPersonalesControlador::class, 'store']);
Route::get('/datos_personales/{id}', [datosPersonalesControlador::class, 'show']);
Route::put('/datos_personales/{id}', [datosPersonalesControlador::class, 'update']);
Route::delete('/datos_personales/{id}', [datosPersonalesControlador::class, 'destroy']);




Route::get('/tipo_documento', [tipoDocumentoControlador::class, 'index']);
Route::post('/tipo_documento', [tipoDocumentoControlador::class, 'store']);
Route::get('/tipo_documento/{id}', [tipoDocumentoControlador::class, 'show']);
Route::put('/tipo_documento/{id}', [tipoDocumentoControlador::class, 'update']);
Route::delete('/tipo_documento/{id}', [tipoDocumentoControlador::class, 'destroy']);



Route::get('/log_errores', [logErroresControlador::class, 'index']);
Route::post('/log_errores', [logErroresControlador::class, 'store']);
Route::get('/log_errores/{id}', [logErroresControlador::class, 'show']);
Route::put('/log_errores/{id}', [logErroresControlador::class, 'update']);
Route::delete('/log_errores/{id}', [logErroresControlador::class, 'destroy']);

Route::get('/agenda_consulta', [agendaControlador::class, 'index']);
Route::post('/agenda_consulta', [agendaControlador::class, 'store']);
Route::get('/agenda_consulta/{id_agenda}', [agendaControlador::class, 'show']);
Route::put('/agenda_consulta/{id_agenda}', [agendaControlador::class, 'update']);
Route::delete('/agenda_consulta/{id_agenda}', [agendaControlador::class, 'destroy']);



Route::get('/antecedentes', [antecedentesControlador::class, 'index']);
Route::post('/antecedentes', [antecedentesControlador::class, 'store']);
Route::get('/antecedentes/{id}', [antecedentesControlador::class, 'show']);
Route::put('/antecedentes/{id}', [antecedentesControlador::class, 'update']);
Route::delete('/antecedentes/{id}', [antecedentesControlador::class, 'destroy']);


Route::get('/formula_optica', [formulaOpticaControlador::class, 'index']);
Route::post('/formula_optica', [formulaOpticaControlador::class, 'store']);
Route::get('/formula_optica/{id}', [formulaOpticaControlador::class, 'show']);
Route::put('/formula_optica/{id}', [formulaOpticaControlador::class, 'update']);
Route::delete('/formula_optica/{id}', [formulaOpticaControlador::class, 'destroy']);



Route::get('/historia_clinica', [historiaClinicaControlador::class, 'index']);
Route::post('/historia_clinica', [historiaClinicaControlador::class, 'store']);
Route::get('/historia_clinica/{id}', [historiaClinicaControlador::class, 'show']);
Route::put('/historia_clinica/{id}', [historiaClinicaControlador::class, 'update']);
Route::delete('/historia_clinica/{id}', [historiaClinicaControlador::class, 'destroy']);