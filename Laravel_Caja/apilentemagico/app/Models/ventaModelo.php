<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;


class ventaModelo extends Model
{
    use HasFactory;
    protected $table = 'venta';
    public $timestamps = true;
    protected $primaryKey = 'id_venta';
    public $incrementing = true;
    protected $keyType = 'int';
    protected $fillable = [
        'fecha_venta',
        'id_usuario',
        'id_cliente',
        'estado'
    ];
}
