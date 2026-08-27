<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;


class AgendaConsultaModelo extends Model
{
    use HasFactory;
    protected $table = 'agenda_consulta';
    public $timestamps = true;
    protected $primaryKey = 'id_agenda';
    public $incrementing = true;
    protected $keyType = 'int';
    protected $fillable = [
        'fecha_hora',
        'id_cliente',
        'motivo',
        'estado'
    ];
}
