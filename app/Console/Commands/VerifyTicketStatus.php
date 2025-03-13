<?php

namespace App\Services;

use App\Models\AccountTicket;
use App\Models\CreateAccount;
use App\Models\ValidateAccount;
use App\Services\GLPIService;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Carbon;

class VerifyTicketStatus
{
    private GLPIService $GLPIService;

    public function __construct()
    {
        $this->GLPIService = new GLPIService();
    }

    public function checkAndProcessTicket(CreateAccount|ValidateAccount $account): void
    {
        if (!$account->pending_sent_at) {
            $this->sendPendingTicket($account);
            return;
        }

        if (Carbon::parse($account->pending_sent_at)->diffInHours(now()) >= 48) {
            $this->sendRejectedTicket($account);
        }
    }

    private function sendPendingTicket(CreateAccount|ValidateAccount $account): void
    {
        $response = $this->GLPIService->createTicket($this->pendingTemplate($account));
        $account->update(['pending_sent_at' => now()]);
        Log::info('Plantilla de pendiente enviada', ['ticket' => $response]);
    }

    private function sendRejectedTicket(CreateAccount|ValidateAccount $account): void
    {
        $existingRejection = AccountTicket::where('account_id', $account->id)
            ->where('ticket_state', 'REJECTED')
            ->exists();

        if (!$existingRejection) {
            $response = $this->GLPIService->createTicket($this->rejectedTemplate($account));
            Log::info('Plantilla de rechazo enviada', ['ticket' => $response]);
        }
    }

    private function pendingTemplate(CreateAccount|ValidateAccount $account): array
    {
        return [
            'input' => [
                'name' => "Validación en curso - SECOP",
                'content' => "Se está validando la información del usuario en SECOP.\n\n"
                    . "**Datos del Usuario:**\n"
                    . "- Regional: {$account->rgn_id}\n"
                    . "- Nombre: {$account->primer_nombre} {$account->segundo_nombre}\n"
                    . "- Apellido: {$account->primer_apellido} {$account->segundo_apellido}\n"
                    . "- Usuario: {$account->usuario}",
                'status' => 1,
                'urgency' => 4,
                'impact' => 3,
                'requesttypes_id' => 1,
            ]
        ];
    }

    private function rejectedTemplate(CreateAccount|ValidateAccount $account): array
    {
        return [
            'input' => [
                'name' => "Rechazo de validación - SECOP",
                'content' => "La validación del contrato en SECOP no fue aprobada después de 48 horas.\n\n"
                    . "**Datos del Usuario:**\n"
                    . "- Regional: {$account->rgn_id}\n"
                    . "- Nombre: {$account->primer_nombre} {$account->segundo_nombre}\n"
                    . "- Apellido: {$account->primer_apellido} {$account->segundo_apellido}\n"
                    . "- Usuario: {$account->usuario}",
                'status' => 2,
                'urgency' => 4,
                'impact' => 3,
                'requesttypes_id' => 1,
            ]
        ];
    }
}
