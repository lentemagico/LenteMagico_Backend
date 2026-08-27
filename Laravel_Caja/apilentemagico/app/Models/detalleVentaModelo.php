<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;


class detalleVentaModelo extends Model
{
    use HasFactory;
    protected $table = 'detalle_venta';
    public $timestamps = false;
    protected $primaryKey = 'id_detalle';
    public $incrementing = true;
    protected $keyType = 'int';
    protected $fillable = [
        'id_venta',
        'id_producto',
        'cantidad',
        'precio_unitario'
    ];
}
