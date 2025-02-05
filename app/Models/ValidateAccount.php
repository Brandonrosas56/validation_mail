<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ValidateAccount extends Model
{
    use HasFactory;

    protected $table = 'validate_account';

    protected $fillable = [
        'regional',
        'primer_nombre',
        'segundo_nombre',
        'primer_apellido',
        'segundo_apellido',
        'correo_personal',
        'correo_institucional',
        'numero_contrato',
        'fecha_inicio_contrato',
        'fecha_terminacion_contrato',
        'usuario'
    ];
}
