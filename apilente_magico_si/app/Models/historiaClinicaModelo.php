<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class historiaClinicaModelo extends Model
{
    //
    use HasFactory;
    protected $table = 'historia_clinica';
    public $timestamps = true;
    protected $primaryKey = 'id_historia';
    public $incrementing = true;
    protected $keyType = 'int';
    protected $fillable = [
        'id_cliente',
        'fecha_apertura',
        'evolucion',
        'num_consulta'
    ];
}