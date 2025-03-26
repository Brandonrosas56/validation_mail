<?php

namespace App\Services;

use App\Models\User;
use GuzzleHttp\Client;
use App\Models\AccountTicket;
use App\Models\CreateAccount;
use App\Models\ValidateAccount;
use GuzzleHttp\Exception\RequestException;

class AccountTicketService
{
    protected $Model;

    /**
     * Constructor de la clase.
     *
     * @param AccountTicket $Model - Instancia del modelo de ticket de cuenta
     */
    public function __construct($Model)
    {
        $this->Model = $Model;
    }

    /**
     * Crea un nuevo ticket de cuenta asociado a una cuenta de usuario.
     *
     * @param CreateAccount|ValidateAccount $account - Cuenta asociada al ticket
     * @param array $ticketInfo - Información del ticket
     */
    public static function create(CreateAccount|ValidateAccount $account, array $ticketInfo): void
    {
        $typeAccount = $account instanceof CreateAccount ? CreateAccount::CREATE_ACCOUNT : CreateAccount::VALIDATE_ACCOUNT;

        $data = [
            'account_id' => $account->id,
            'type_account' => $typeAccount,
            'ticket_id' => $ticketInfo['id'],
            'ticket_info' => json_encode($ticketInfo),
            'ticket_state' => $ticketInfo['status'],
        ];
        AccountTicket::create($data);
    }

    /**
     * Obtiene la cuenta asociada a un ticket de cuenta.
     *
     * @return CreateAccount|ValidateAccount - Retorna la cuenta de usuario asociada
     */
    public function getAccount(): CreateAccount|ValidateAccount
    {
        return $this->isCreateAccount() 
            ? CreateAccount::find($this->getModel()->account_id) 
            : ValidateAccount::find($this->getModel()->account_id);
    }

    /**
     * Verifica si el ticket pertenece a una cuenta de creación.
     *
     * @return bool - Retorna true si la cuenta es de tipo creación, false si es de validación
     */
    function isCreateAccount(): bool
    {
        return $this->getModel()->type_account == CreateAccount::CREATE_ACCOUNT;
    }

    /**
     * Actualiza la información del ticket de cuenta.
     *
     * @param array $ticketInfo - Información actualizada del ticket
     * @return bool - Retorna true si la actualización fue exitosa
     */
    public function updateTicketInfo($ticketInfo): bool
    {
        $data = [
            'ticket_info' => json_encode($ticketInfo),
            'ticket_state' => $ticketInfo['status'],
        ];
        $this->getModel()->update($data);
        return true;
    }

    /**
     * Obtiene la instancia del modelo de ticket de cuenta.
     *
     * @return AccountTicket - Retorna el modelo de ticket asociado
     */
    public function getModel(): AccountTicket
    {
        return $this->Model;
    }
}
