<?php

namespace App\Console\Commands;

use App\Models\CreateAccount;
use App\Models\ValidateAccount;
use App\Services\SecopService;
use Illuminate\Console\Command;
use App\Services\SendValidationStatusService;
use App\Services\GLPIService;
use Log;

class RetryValidation extends Command
{
    protected $signature = 'app:retry-validation';
    protected $description = 'Command description';

    public function handle()
    {
        $this->verifyCreatedAccounts();
        $this->verifyValidatedAccounts();
    }
    private function verifyCreatedAccounts(): bool
    {
        $CreateAccounts = CreateAccount::all();

        foreach ($CreateAccounts as $CreateAccount) {
            if (in_array($CreateAccount->estado, ['exitoso', 'rechazado'])) {
                if ($CreateAccount->estado == 'exitoso') {
                    $this->sendSuccessTemplate($CreateAccount);
                } elseif ($CreateAccount->estado == 'rechazado') {
                    $this->sendRejectTemplate($CreateAccount);
                }

                // Verificar que id no sea null antes de cerrar el ticket
                if ($CreateAccount->id !== null) {
                    $glpiService = new GLPIService();
                    $glpiService->closeTicket($CreateAccount->id);
                    Log::info('Ticket cerrado en GLPI para la cuenta', ['account_id' => $CreateAccount->id]);
                } else {
                    Log::warning('El id es null para la cuenta', ['account_id' => $CreateAccount->id]);
                }
            }
        }
        return true;
    }

    private function verifyValidatedAccounts(): bool
    {
        $ValidateAccounts = ValidateAccount::all();

        foreach ($ValidateAccounts as $ValidateAccount) {
            var_dump($ValidateAccount);
            if (in_array($ValidateAccount->estado, ['exitoso', 'rechazado'])) {
                if ($ValidateAccount->estado == 'exitoso') {
                    $this->sendSuccessTemplate($ValidateAccount);
                } elseif ($ValidateAccount->estado == 'rechazado') {
                    $this->sendRejectTemplate($ValidateAccount);
                }

                // Verificar que id no sea null antes de cerrar el ticket

                if ($ValidateAccount->id !== null) {
                    $glpiService = new GLPIService();
                    $glpiService->closeTicket($ValidateAccount->id); 
                    Log::info('Ticket cerrado en GLPI para la cuenta validada', ['account_id' => $ValidateAccount->id]);
                } else {
                    Log::warning('El id es null para la cuenta validada', ['account_id' => $ValidateAccount->id]);
                }
            }
        }
        return true;
    }


    private function sendSuccessTemplate($Account): void
    {
       
        $sendValidationStatusService = new SendValidationStatusService($Account, SendValidationStatusService::TEMPLATE_SUCCESS_CONTRACTOR_CREACION_CLOSE);
        $sendValidationStatusService->sendTicket(); 
    
        $glpiService = new GLPIService();
        $glpiService->closeTicket($Account->ticket_id);

        Log::info('Plantilla de éxito para creación de contratista enviada', ['ticket_id' => $Account->ticket_id]);
        Log::info('Ticket cerrado en GLPI para la cuenta', ['ticket_id' => $Account->ticket_id]);
    }

    private function sendRejectTemplate($Account): void
    {
        
        $sendValidationStatusService = new SendValidationStatusService($Account, SendValidationStatusService::TEMPLATE_REJECTED_CONTRACTOR_CREACION);
        $sendValidationStatusService->sendTicket(); 


        $glpiService = new GLPIService();
        $glpiService->closeTicket($Account->ticket_id);

        Log::info('Plantilla de rechazo para creación de contratista enviada', ['ticket_id' => $Account->ticket_id]);
        Log::info('Ticket cerrado en GLPI para la cuenta', ['ticket_id' => $Account->ticket_id]);
    }
}