<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class CreateAccount extends Model
{
    use HasFactory;

    protected $table = 'create_account';

    protected $fillable = [
        'rgn_id',
        'primer_nombre',
        'segundo_nombre',
        'primer_apellido',
        'segundo_apellido',
        'documento_proveedor',
        'correo_personal',
        'correo_institucional',
        'numero_contrato',
        'fecha_inicio_contrato',
        'fecha_terminacion_contrato',
    ];

    public function regional()
    {
        return $this->belongsTo(Regional::class, 'rgn_id', 'rgn_id');
    }
}
