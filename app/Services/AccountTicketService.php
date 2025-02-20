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

    public function __construct($Model)
    {
        $this->Model = $Model;
    }

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

    public function getAccount(): CreateAccount|ValidateAccount
    {
        return $this->isCreateAccount() ? CreateAccount::find($this->getModel()->account_id) : ValidateAccount::find($this->getModel()->account_id);

    }

    function isCreateAccount(): bool
    {
        return $this->getModel()->type_account == CreateAccount::CREATE_ACCOUNT;
    }

    public function updateTicketInfo($ticketInfo): bool
    {
        $data = [
            'ticket_info' => json_encode($ticketInfo),
            'ticket_state' => $ticketInfo['status'],
        ];
        $this->getModel()->update($data);
        return true;
    }

    public function getModel(): AccountTicket
    {
        return $this->Model;
    }
}
