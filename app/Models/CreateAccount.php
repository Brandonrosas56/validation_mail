<?php

namespace App\Models;

use App\Services\CreateAccountService;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class CreateAccount extends Model
{
    use HasFactory;

    /**
     * Estados posibles de una cuenta.
     */
    const PENDIENTE = 'pendiente';
    const RECHAZADO = 'rechazado';
    const RECHAZO = 'rechazo';
    const EXITOSO = 'exitoso';
    const EXITO = 'éxito';
    const REVISION = 'revisión';
    const INDETERMINADO = 'indeterminado';

    /**
     * Tipos de cuentas.
     */
    const CREATE_ACCOUNT = 1;
    const VALIDATE_ACCOUNT = 2;

    /**
     * Estados manuales disponibles.
     */
    const MANUAL_STATES = [
        self::RECHAZADO,
        self::EXITOSO
    ];

    /**
     * Nombre de la tabla asociada al modelo.
     *
     * @var string
     */
    protected $table = 'create_account';

    /**
     * Atributos protegidos contra asignación masiva.
     *
     * @var array
     */
    protected $guarded = [];

    /**
     * Relación con la tabla de regionales.
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function regional()
    {
        return $this->belongsTo(Regional::class, 'rgn_id', 'rgn_id');
    }

    /**
     * Relación con la tabla de tickets de cuenta.
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function accountTickets()
    {
        return $this->belongsTo(AccountTicket::class, 'create_account_id', 'create_account_id');
    }

    /**
     * Obtiene el servicio asociado a la cuenta creada.
     *
     * @return CreateAccountService
     */
    public function getService(): CreateAccountService
    {
        return new CreateAccountService($this);
    }
}
