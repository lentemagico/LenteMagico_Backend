<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class formulaOpticaModelo extends Model
{
    //
    use HasFactory;
    protected $table = 'formula_optica';
    public $timestamps = true;
    protected $primaryKey = 'id_formula';
    public $incrementing = true;
    protected $keyType = 'int';
    protected $fillable = [
        'id_consulta',
        'id_cliente',
        'esfera_ojo_derecho_e_izquierdo',
        'cilindro_ojo_derecho_e_izquierdo',
        'eje_ojo_derecho_e_izquierdo',
        'adicion',
        'tipo_lente',
        'uso',
        'observaciones',
        'fecha_generacion'
    ];
}