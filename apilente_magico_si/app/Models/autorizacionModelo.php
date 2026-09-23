<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class autorizacionModelo extends Model
{
    //
    use HasFactory;
    protected $table = 'autorizacion';
    public $timestamps = false;
    protected $primaryKey = 'id';  
    public $incrementing = true;
    protected $keyType = 'int';
    protected $fillable = [
        'id',
        'nombre',
    ];

}
