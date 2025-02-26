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

    const INDETERMINADO = 'indeterminado';

    const CREATE_ACCOUNT = 1;
    const VALIDATE_ACCOUNT = 2;

    const MANUAL_STATES = [
        self::RECHAZADO,
        self::EXITOSO
    ];

    use HasFactory;

    protected $table = 'create_account';

    protected $guarded = [];

   

    public function regional()
    {
        return $this->belongsTo(Regional::class, 'rgn_id', 'rgn_id');
    }

    public function accountTickets()
    {
        return $this->belongsTo(AccountTicket::class, 'create_account_id', 'create_account_id');
    }

    public function getService(): CreateAccountService
    {
        return new CreateAccountService($this);
    }


}
