<?php

namespace App\Models;

use App\Services\CreateAccountService;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class CreateAccount extends Model
{
    const PENDIENTE = 'pendiente';

    const RECHAZADO = 'rechazado';
    const RECHAZO = 'rechazo';
    const EXITOSO = 'exitoso';
    const EXITO = 'éxito';
    const REVISION = 'revisión';
    

    const MANUAL_STATES= [
        self::RECHAZADO,
        self::EXITOSO
    ];

    use HasFactory;

    protected $table = 'create_account';

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
        'estado'
    ];

    public function regional()
    {
        return $this->belongsTo(Regional::class, 'rgn_id', 'rgn_id');
    }

    public function accountTickets()
    {
        return $this->belongsTo(AccountTicket::class, 'create_account_id', 'create_account_id');
    }

   public function getService() : CreateAccountService {
        return new CreateAccountService($this);
    }

   
}
