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
        Log::info("aca estamos");
        $CreateAccounts = CreateAccount::all();

        foreach ($CreateAccounts as $CreateAccount) {
            Log::info('Validación:', [
                'id' => $CreateAccount->id,
                'estado' => $CreateAccount->estado,
                'ticket_id' => $CreateAccount->ticket_id,
            ]);
            
            if (in_array($CreateAccount->estado, ['exito', 'rechazo', 'revision'])) {
                // Primero enviar las plantillas correspondientes
                if ($CreateAccount->estado == 'exito') {
                    $this->sendSuccessTemplate($CreateAccount);
                } elseif ($CreateAccount->estado == 'rechazo') {
                    $this->sendRejectTemplate($CreateAccount);
                } elseif ($CreateAccount->estado == 'revision') {
                    $this->sendReviewTemplate($CreateAccount);
                }

                // Luego actualizar el estado en la base de datos
                if ($CreateAccount->estado == 'exito') {
                    $CreateAccount->estado = 'exitoso';
                } elseif ($CreateAccount->estado == 'rechazo') {
                    $CreateAccount->estado = 'rechazado';
                } elseif ($CreateAccount->estado == 'revision') {
                    $CreateAccount->estado = 'en_revision';
                }
                
                // Guardar los cambios en la base de datos
                $CreateAccount->save();

                // Verificar que id no sea null antes de cerrar el ticket
                if ($CreateAccount->id) {
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
            Log::info('Validación:', [
                'id' => $ValidateAccount->id,
                'estado' => $ValidateAccount->estado,
                'ticket_id' => $ValidateAccount->id,
            ]);
           
            if (in_array($ValidateAccount->estado, ['exito', 'rechazo', 'revision'])) {
                // Primero enviar las plantillas correspondientes
                if ($ValidateAccount->estado == 'exito') {
                    $this->sendSuccessTemplate($ValidateAccount);
                } elseif ($ValidateAccount->estado == 'rechazo') {
                    $this->sendRejectTemplate($ValidateAccount);
                } elseif ($ValidateAccount->estado == 'revision') {
                    $this->sendReviewTemplate($ValidateAccount);
                }

                // Luego actualizar el estado en la base de datos
                if ($ValidateAccount->estado == 'exito') {
                    $ValidateAccount->estado = 'exitoso';
                } elseif ($ValidateAccount->estado == 'rechazo') {
                    $ValidateAccount->estado = 'rechazado';
                } elseif ($ValidateAccount->estado == 'revision') {
                    $ValidateAccount->estado = 'en_revision';
                }
        
                
                // Guardar los cambios en la base de datos
                $ValidateAccount->save();

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
        $glpiService->closeTicket($Account->id);

        Log::info('Plantilla de éxito para creación de contratista enviada', ['ticket_id' => $Account->ticket_id]);
        Log::info('Ticket cerrado en GLPI para la cuenta', ['ticket_id' => $Account->ticket_id]);
    }

    private function sendRejectTemplate($Account): void
    {
        $sendValidationStatusService = new SendValidationStatusService($Account, SendValidationStatusService::TEMPLATE_REJECTED_FUNCTIONARY_ACTIVACION);
        $sendValidationStatusService->sendTicket(); 

        $glpiService = new GLPIService();
        $glpiService->closeTicket($Account->id);

        Log::info('Plantilla de rechazo para creación de contratista enviada', ['ticket_id' => $Account->ticket_id]);
        Log::info('Ticket cerrado en GLPI para la cuenta', ['ticket_id' => $Account->ticket_id]);
    }

    private function sendReviewTemplate($Account): void
    {
        $sendValidationStatusService = new SendValidationStatusService($Account, SendValidationStatusService::TEMPLATE_REVIEW_ACTIVACION_CREATION);
        $sendValidationStatusService->sendTicket(); 

        $glpiService = new GLPIService();
        $glpiService->closeTicket($Account->id);

        Log::info('Plantilla de revisión para activación/creación enviada', ['ticket_id' => $Account->ticket_id]);
        Log::info('Ticket cerrado en GLPI para la cuenta', ['ticket_id' => $Account->ticket_id]);
    }
}