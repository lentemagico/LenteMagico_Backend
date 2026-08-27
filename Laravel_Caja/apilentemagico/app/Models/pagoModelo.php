<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class pagoModelo extends Model
{
    use HasFactory;
    protected $table = 'pago';
    public $timestamps = true;
    protected $primaryKey = 'id_pago';
    public $incrementing = true;
    protected $keyType = 'int';
    protected $fillable = [
        'id_venta',
        'fecha_pago',
        'monto',
        'metodo_pago',
        'monto_recibido',
        'cambio'
    ];
}
