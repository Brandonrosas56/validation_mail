<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Solicitante extends Model
{
    use HasFactory;

    protected $fillable = [
        'regional',
        'primer_nombre',
        'segundo_nombre',
        'primer_apellido',
        'segundo_apellido',
        'correo_personal',
        'numero_contrato',
        'fecha_inicio_contrato',
        'fecha_terminacion_contrato',
    ];
}

