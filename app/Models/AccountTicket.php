<?php

namespace App\Models;

use App\Services\AccountTicketService;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class AccountTicket extends Model
{
    use HasFactory;

    /**
     * Estados posibles de un ticket.
     */
    const NUEVO = 1;
    const EN_CURSO = 2;
    const PLANIFICADA = 3;
    const EN_ESPERA = 4;
    const RESUELTO = 5;
    const CERRADO = 6;
    const CERRADO_SIN_SOLUCION = 7;
    const CERRADO_SIN_COMENTARIO = 8;

    /**
     * Estados abiertos de un ticket.
     */
    const OPEN_STATES = [
        self::NUEVO,
        self::EN_CURSO,
        self::PLANIFICADA,
        self::EN_ESPERA,
        self::CERRADO_SIN_SOLUCION,
        self::CERRADO_SIN_COMENTARIO
    ];

    /**
     * Estados cerrados de un ticket.
     */
    const CLOSE_STATES = [
        self::RESUELTO,
        self::CERRADO
    ];

    /**
     * Atributos que pueden asignarse masivamente.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'account_id',
        'type_account',
        'ticket_id',
        'ticket_state',
        'ticket_info'
    ];

    /**
     * Obtiene el servicio asociado al ticket de cuenta.
     *
     * @return AccountTicketService
     */
    public function getService(): AccountTicketService
    {
        return new AccountTicketService($this);
    }
}
