<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ClienteModelo extends Model
{
    use HasFactory;
    protected $table = 'cliente';
    public $timestamps = true;
    protected $primaryKey = 'id_cliente';
    public $incrementing = true;
    protected $keyType = 'int';
    protected $fillable = [
        'fecha_registro',
        'id_datos_personales'
    ];
}
