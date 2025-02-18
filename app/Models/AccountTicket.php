<?php

namespace App\Models;

use App\Services\AccountTicketService;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class AccountTicket extends Model
{
    use HasFactory;

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
