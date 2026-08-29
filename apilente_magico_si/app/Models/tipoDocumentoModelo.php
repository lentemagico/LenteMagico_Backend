<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class tipoDocumentoModelo extends Model
{
 //
    use HasFactory;
    protected $table = 'tipo_documento';
    public $timestamps = true;
     protected $primaryKey = 'id';
    public $incrementing = true;
    protected $keyType = 'int';
    protected $fillable = [
        'sigla',
        'nombre_documento',
        'estado'
    ];
}
