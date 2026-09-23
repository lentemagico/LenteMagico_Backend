<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class antecedentesModelo extends Model
{
    //
    use HasFactory;

    protected $table = 'antecedentes';
    public $timestamps = true;
    protected $primaryKey = 'id_antecedentes';
    public $incrementing = true;
    protected $keyType = 'int';
    protected $fillable = [
        'id_historia',
        'antecedentes'
    ];
}