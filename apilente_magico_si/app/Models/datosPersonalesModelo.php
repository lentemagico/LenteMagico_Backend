<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class datosPersonalesModelo extends Model
{
    //
    //
    use HasFactory;
    protected $table = 'datos_personales';
    public $timestamps = false;
     protected $primaryKey = 'id';
    public $incrementing = true;
    protected $keyType = 'int';
    protected $fillable = [
        'id_tipo_documento',
        'numero_documento',
        'primer_nombre',
        'segundo_nombre',
        'primer_apellido',
        'segundo_apellido',
        'fecha_nacimiento',
        'genero',
        'correo',
        'telefono'
    ];
}
