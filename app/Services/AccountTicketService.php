<?php

namespace App\Services;

use App\Models\User;
use GuzzleHttp\Client;
use App\Models\AccountTicket;
use App\Models\CreateAccount;
use GuzzleHttp\Exception\RequestException;

class AccountTicketService
{
    protected $Model;

    public function __construct($Model) {
        $this->Model = $Model;
    }

    public static function create(CreateAccount $createAccount, array $ticketInfo) : void {
        $data = [
            'create_account_id' => $createAccount->id,
            'ticket_id'=> $ticketInfo['id'],
            'ticket_info'=> json_encode($ticketInfo),
            'ticket_state'=> $ticketInfo['status'],
        ];
        AccountTicket::created($data);
    }

    public function getModel() : CreateAccount {
        return $this->Model;
    }
}
