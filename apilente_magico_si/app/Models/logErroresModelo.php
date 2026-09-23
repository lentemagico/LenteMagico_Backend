<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class logErroresModelo extends Model
{
    //
    use HasFactory;
    protected $table = 'log_errores';
    public $timestamps = true;
     protected $primaryKey = 'id';
    public $incrementing = true;
    protected $keyType = 'int';
    protected $fillable = [
        'id_usuario',
        'nivel',
        'nombre_usuario',
        'mensaje',
        'fecha'
    ];
}
