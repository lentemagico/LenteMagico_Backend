<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class agendaConsultaModelo extends Model
{
    //
    use HasFactory;
    protected $table = 'agenda_consulta';
    public $timestamps = false;  
    public $incrementing = true;
    protected $primaryKey = 'id_agenda';  
    protected $keyType = 'int';
    protected $fillable = [
        'id_cliente',
        'fecha_hora',
        'motivo',
        'estado'
    ];
}
