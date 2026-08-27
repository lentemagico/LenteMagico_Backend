<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

use App\Http\Controllers\clienteControlador;
use App\Http\Controllers\AgendaConsultaControlador;
use App\Http\Controllers\detalleVentaControlador;
use App\Http\Controllers\facturaControlador;
use App\Http\Controllers\pagoControlador;
use App\Http\Controllers\ventaControlador;


// ===============================
// USUARIO
// ===============================

Route::get('/user', function (Request $request) {
    return $request->user();
})->middleware('auth:sanctum');


// ===============================
// CLIENTES
// ===============================

Route::get('/cliente', [clienteControlador::class, 'index']);

Route::post('/cliente', [clienteControlador::class, 'store']);

Route::put('/cliente/{id_cliente}', [clienteControlador::class, 'update']);

Route::delete('/cliente/{id_cliente}', [clienteControlador::class, 'destroy']);


// ===============================
// AGENDAR CONSULTAS
// ===============================

// Obtener todas las consultas
Route::get('/agendaConsulta', [agendaConsultaControlador::class, 'index']);

// Crear una consulta
Route::post('/agendaConsulta', [agendaConsultaControlador::class, 'store']);

// Obtener una consulta específica
Route::get('/agendaConsulta/{id_agenda}', [agendaConsultaControlador::class, 'show']);

// Actualizar una consulta
Route::put('/agendaConsulta/{id_agenda}', [agendaConsultaControlador::class, 'update']);

// Eliminar una consulta
Route::delete('/agendaConsulta/{id_agenda}', [agendaConsultaControlador::class, 'destroy']);

// ===============================
// DETALLE VENTA    
// ===============================

Route::get('/detalleVenta', [detalleVentaControlador::class, 'index']);

Route::post('/detalleVenta', [detalleVentaControlador::class, 'store']);

Route::put('/detalleVenta/{id_detalle}', [detalleVentaControlador::class, 'update']);

Route::delete('/detalleVenta/{id_detalle}', [detalleVentaControlador::class, 'destroy']);

// ===============================
// FACTURA
// ================================

Route::get('/factura', [facturaControlador::class, 'index']);

Route::post('/factura', [facturaControlador::class, 'store']);

Route::put('/factura/{id_factura}', [facturaControlador::class, 'update']);

Route::delete('/factura/{id_factura}', [facturaControlador::class, 'destroy']);

// ===============================
// PAGO 
// ================================

Route::get('/pago', [pagoControlador::class, 'index']);

Route::post('/pago', [pagoControlador::class, 'store']);

Route::put('/pago/{id_pago}', [pagoControlador::class, 'update']);

Route::delete('/pago/{id_pago}', [pagoControlador::class, 'destroy']);

// ===============================
// VENTA
// ================================

Route::get('/venta', [ventaControlador::class, 'index']);

Route::post('/venta', [ventaControlador::class, 'store']);

Route::put('/venta/{id_venta}', [ventaControlador::class, 'update']);

Route::delete('/venta/{id_venta}', [ventaControlador::class, 'destroy']);



