<?php

namespace App\Models;

use App\Services\AccountTicketService;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class AccountTicket extends Model
{
    use HasFactory;

    const NUEVO = 1;
    const EN_CURSO = 2;
    const PLANIFICADA = 3;
    const EN_ESPERA = 4;
    const RESUELTO = 5;
    const CERRADO = 6;
    const CERRADO_SIN_SOLUCION = 7;
    const CERRADO_SIN_COMENTARIO = 8;

    const OPEN_STATES = [
        self::NUEVO,
        self::EN_CURSO,
        self::PLANIFICADA,
        self::EN_ESPERA,
        self::CERRADO_SIN_SOLUCION,
        self::CERRADO_SIN_COMENTARIO
    ];

    const CLOSE_STATES = [
        self::RESUELTO,
        self::CERRADO
    ];

    protected $fillable = [
        'create_account_id',
        'ticket_id',
        'ticket_state',
        'ticket_info'
    ];


    public function getService(): AccountTicketService
    {
        return new AccountTicketService($this);
    }

}
