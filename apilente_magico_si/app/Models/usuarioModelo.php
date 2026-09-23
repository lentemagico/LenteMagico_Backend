<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
// use Illuminate\Support\Facades\Hash;

class usuarioModelo extends Model
{
    //
    use HasFactory;
    protected $table = 'usuario';
    public $timestamps = false;
     protected $primaryKey = 'id';
    public $incrementing = true;
    protected $keyType = 'int';
    protected $fillable = [
        'id_datos_personales',
        'contrasenia',
        'activar_usuario',
        'clave_idioma',
        'clave_activacion',
        'llave_reinicio',
        'hora_reinicio'
    ];

    /**
     * Relacion con el modelo de datos personales
     */
    public function datosPersonales(): BelongsTo
    {
        return $this->belongsTo(datosPersonalesModelo::class, 'id_datos_personales');

        
    }
    // public function setContraseniaAttribute($value)
    // {
    //         $this->attributes['contrasenia'] = Hash::make($value);
    // }



}
