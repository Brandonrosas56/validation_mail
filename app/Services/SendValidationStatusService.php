<?php

namespace App\Services;

use App\Models\AccountTicket;
use App\Models\CreateAccount;
use App\Models\ValidateAccount;
use App\Services\GLPIService;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Carbon;

class SendValidationStatusService
{
    public const TEMPLATE_PENDING = 'PENDING';  // ← Cambia de private a public
    public const TEMPLATE_REJECTED = 'REJECTED';  // ← Cambia de private a public

    private CreateAccount|ValidateAccount $account;
    private string $state = '';
    private GLPIService $GLPIService;

    public function __construct(CreateAccount|ValidateAccount $account, string $state)
    {
        $this->account = $account;
        $this->state = $state;
        $this->GLPIService = new GLPIService();
    }

    public function sendTicket(): void
    {
        switch ($this->state) {
            case self::TEMPLATE_PENDING:
                if (!$this->account->pending_sent_at) {
                    $response = $this->GLPIService->createTicket($this->pendingTemplate());
                    $this->account->update(['pending_sent_at' => now()]);
                    Log::info('Plantilla de pendiente enviada', ['ticket' => $response]);
                }
                break;
            case self::TEMPLATE_REJECTED:
                if ($this->account->pending_sent_at && Carbon::parse($this->account->pending_sent_at)->diffInHours(now()) >= 48) {
                    $response = $this->GLPIService->createTicket($this->rejectedTemplate());
                    Log::info('Plantilla de rechazo enviada', ['ticket' => $response]);
                }
                break;
        }
    }

    private function getUserInfo(): array
    {
        $user = auth()->user();
        return [
            'email' => $user->email ?? 'No disponible',
            'document' => $user->supplier_document ?? 'No disponible',
            'group_id' => $user->glpi_group_id ?? null,
            'user_id' => $user->glpi_user_id ?? null,
        ];
    }

    private function pendingTemplate(): array
    {
        $userInfo = $this->getUserInfo();
        return [
            'input' => [
                'name' => "Validación en curso - SECOP",
                'content' => "Se está validando la información del usuario en SECOP."
                    . "\n**Datos del Usuario:**"
                    . "\n- Regional: {$this->account->rgn_id}"
                    . "\n- Nombre: {$this->account->primer_nombre} {$this->account->segundo_nombre}"
                    . "\n- Apellido: {$this->account->primer_apellido} {$this->account->segundo_apellido}"
                    . "\n- Usuario: {$this->account->usuario}"
                    . "\n\n**Datos del Solicitante:**"
                    . "\n- Correo: {$userInfo['email']}"
                    . "\n- Documento: {$userInfo['document']}",
                    
                'type' => 1, // Tipo de ticket (1 = Incidente, 2 = Requerimiento, etc.)
                'status' => 1,
                'urgency' => 4,
                'impact' => 3,
                'requesttypes_id' => 1,
                'groups_id' => $userInfo['group_id'],
                'users_id_assign' => $userInfo['user_id'],
            ]
        ];
    }

    private function rejectedTemplate(): array
    {
        $userInfo = $this->getUserInfo();
        return [
            'input' => [
                'name' => "Rechazo de validación - SECOP",
                'content' => "La validación del contrato en SECOP no fue aprobada después de 48 horas."
                    . "\n**Datos del Usuario:**"
                    . "\n- Regional: {$this->account->rgn_id}"
                    . "\n- Nombre: {$this->account->primer_nombre} {$this->account->segundo_nombre}"
                    . "\n- Apellido: {$this->account->primer_apellido} {$this->account->segundo_apellido}"
                    . "\n- Usuario: {$this->account->usuario}"

                    . "\n\n**Datos del Solicitante:**"
                    . "\n- Correo: {$userInfo['email']}"
                    . "\n- Documento: {$userInfo['document']}",

                'type' => 1, // Tipo de ticket (1 = Incidente, 2 = Requerimiento, etc.)
                'status' => 2,
                'urgency' => 4,
                'impact' => 3,
                'requesttypes_id' => 1,
                'groups_id' => $userInfo['group_id'],
                'users_id_assign' => $userInfo['user_id'],
            ]
        ];
    }
}
