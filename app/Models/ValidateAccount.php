<?php

namespace App\Models;

use App\Services\ValidateAccountService;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ValidateAccount extends Model
{
    use HasFactory;
    const CONTRACTOR = 'Contratista';
    protected $table = 'validate_account';

    protected $fillable = [
        'rgn_id',
        'primer_nombre',
        'segundo_nombre',
        'primer_apellido',
        'segundo_apellido',
        'documento_proveedor',
        'tipo_documento',
        'correo_personal',
        'correo_institucional',
        'numero_contrato',
        'fecha_inicio_contrato',
        'fecha_terminacion_contrato',
        'rol_asignado',
        'usuario',
        'user_id'
    ];

    public function regional()
    {
        return $this->belongsTo(Regional::class, 'rgn_id', 'rgn_id');
    }

    public function getService(): ValidateAccountService
    {
        return new ValidateAccountService($this);
    }

}
