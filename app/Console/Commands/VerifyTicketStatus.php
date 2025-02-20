<?php

namespace App\Console\Commands;

use App\Models\AccountTicket;
use App\Models\CreateAccount;
use App\Services\GLPIService;
use Illuminate\Console\Command;

class VerifyTicketStatus extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'app:verify-ticket-status';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Command description';

    private GLPIService|null $GLPIService = null;

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $this->GLPIService = new GLPIService();
        $AccountTickets = AccountTicket::whereNotIn('ticket_state', [AccountTicket::CERRADO, AccountTicket::RESUELTO])->get();
        foreach ($AccountTickets as $AccountTicket) {
            $ticketInfo = $this->GLPIService->getTicketInfo($AccountTicket->ticket_id);
            $this->validateTicketStatus($ticketInfo, $AccountTicket);
        }
    }

    private function validateTicketStatus(array $ticketInfo, AccountTicket $AccountTicket): bool
    {
        $AccountTicket->getService()->updateTicketInfo($ticketInfo);
        if ($ticketInfo['status'] != $AccountTicket->ticket_status && in_array($ticketInfo['status'], AccountTicket::CLOSE_STATES)) {
            $this->updateAccountStatus($AccountTicket);
        }
        return true;
    }

    private function updateAccountStatus(AccountTicket $AccountTicket): bool
    {
        $lastFollow = $this->GLPIService->getTicketFollowByLastResponse($AccountTicket->ticket_id);
        if (!empty($lastFollow)) {
            $state = $this->getState(strtolower($lastFollow['content']));
            if ($state != CreateAccount::INDETERMINADO) {
                $Account = $AccountTicket->getService()->getAccount();
                $Account->getService()->changeStatus($state);
            } else {
                $AccountTicket->update(['ticket_state' => AccountTicket::CERRADO_SIN_COMENTARIO]);
            }
        } else {
            $AccountTicket->update(['ticket_state' => AccountTicket::CERRADO_SIN_SOLUCION]);
        }
        return true;
    }

    private function getState(string $content): string
    {
        if (str_contains($content, 'exitoso')) {
            return CreateAccount::EXITO;
        } elseif (str_contains($content, 'rechazado')) {
            return CreateAccount::RECHAZO;
        }
        return CreateAccount::INDETERMINADO;
    }

}
