<?php

namespace App\Services;

use App\Models\account;
use GuzzleHttp\Client;
use App\Models\AccountTicket;
use App\Models\CreateAccount;
use App\Models\ValidateAccount;
use Illuminate\Support\Facades\Log;
use GuzzleHttp\Exception\RequestException;

class SendValidationStatusService
{
    private const TEMPLATE_SUCCESS = 'success';
    const SECOP_ERROR = 'SECOP_ERROR';
    const NEMOTECNIA_ERROR = 'ERRRO_NEMOTECNIA';

    const NEMOTECNIA_ERROR_FUN = 'NEMOTECNIA_ERROR_FUN';
    const RECJECTED_ERROR = 'RECJECTED_ERROR';
    const VALIDATION_SUCCESS = 'VALIDATION_OK';
    private Createaccount|ValidateAccount $account;
    private string $state = '';
    private bool $contractor = false;
    private GLPIService $GLPIService;

    public function __construct(CreateAccount|ValidateAccount $account, string $state, $contractor = false)
    {
        $this->account = $account;
        $this->state = $state;
        $this->contractor = $contractor;
        $this->GLPIService = new GLPIService();
    }

    public function sendTicket(): void
    {
        switch ($this->state) {
            case self::VALIDATION_SUCCESS:
                // Usar la plantilla successTemplate
                $response = $this->GLPIService->createTicket($this->successTemplate());
                break;
            case self::SECOP_ERROR:
                $response = $this->GLPIService->createTicket($this->secopTemplate());
                Log::error(json_encode($response));
                $ticketInfo = $this->GLPIService->getTicketInfo($response['id']);
                AccountTicketService::create($this->account, $ticketInfo);
                break;
            case self::NEMOTECNIA_ERROR:
                $template = $this->contractor ? $this->nemotecniaTemplateContractor() : $this->nemotecniaTemplaFun();
                $response = $this->GLPIService->createTicket($template);
                $ticketInfo = $this->GLPIService->getTicketInfo($response['id']);
                AccountTicketService::create($this->account, $ticketInfo);
                break;
            case self::NEMOTECNIA_ERROR_FUN:
                // Usar la plantilla nemotecniaTemplateContractor
                $this->GLPIService->createTicket($this->nemotecniaTemplaFun());
                break;
        }
    }

    private function successTemplate(): array
    {
        $user = auth()->user(); // Obtiene el usuario logueado
        $userEmail = $user->email; // Correo del usuario
        $userDocumentNumber = $user->supplier_document; // Número de documento del usuario
        
        return [
            'input' => [
                'name' => "Caso pendiente validacion SECOP",
                'content' => "
                    *Datos del Usuario:*
                    * *Regional:* {$this->account->rgn_id}
                    * *Primer Nombre:* {$this->account->primer_nombre}
                    * *Segundo Nombre:* {$this->account->segundo_nombre}
                    * *Primer Apellido:* {$this->account->primer_apellido}
                    * *Segundo Apellido:* {$this->account->segundo_apellido}
                    * *Usuario:* {$this->account->usuario}
        
                    *Datos del Solicitante:*
                    * *Correo del Solicitante:* {$userEmail}
                    * *Número de Documento:* {$userDocumentNumber}
                ",
                'type' => 1, // Tipo de ticket (1 = Incidente, 2 = Requerimiento, etc.)
                'status' => 1, // Estado del ticket (1 = Nuevo)
                'urgency' => 4, // Urgencia (4 = Media)
                'impact' => 3, // Impacto (3 = Medio)
                'requesttypes_id' => 1, // Tipo de solicitud (1 = Manual/API)
                'groups_id' => $user->glpi_group_id, // Asignar dinámicamente el grupo
                'users_id_assign' => $user->glpi_user_id, // Asignar dinámicamente el usuario en GLPI
       
            ]
        ];
}
    
    private function secopTemplate(): array
    {
        $user = auth()->user(); // Obtiene el usuario logueado
        $userEmail = $user->email; // Correo del usuario
        $userDocumentNumber = $user->supplier_document; // Número de documento del usuario
        
        return [
            'input' => [
                'name' => "Caso por rechazo SECOP",
                'content' => "
                    *Datos del Usuario:*
                    * *Regional:* {$this->account->rgn_id}
                    * *Primer Nombre:* {$this->account->primer_nombre}
                    * *Segundo Nombre:* {$this->account->segundo_nombre}
                    * *Primer Apellido:* {$this->account->primer_apellido}
                    * *Segundo Apellido:* {$this->account->segundo_apellido}
                    * *Usuario:* {$this->account->usuario}
        
                    *Datos del Solicitante:*
                    * *Correo del Solicitante:* {$userEmail}
                    * *Número de Documento:* {$userDocumentNumber}
                ",
                'type' => 1, // Tipo de ticket (1 = Incidente, 2 = Requerimiento, etc.)
                'status' => 1, // Estado del ticket (1 = Nuevo)
                'urgency' => 4, // Urgencia (4 = Media)
                'impact' => 3, // Impacto (3 = Medio)
                'requesttypes_id' => 1, // Tipo de solicitud (1 = Manual/API)
                'groups_id' => $user->glpi_group_id, // Asignar dinámicamente el grupo
                'users_id_assign' => $user->glpi_user_id, // Asignar dinámicamente el usuario en GLPI
       
            ]
        ];
}
}