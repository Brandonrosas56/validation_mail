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
        return [
            'input' => [
                'name' => "Caso por Nemotecnia - Contratista (Fallido)",
                'content' => "
                        *Datos del Usuario:*
                        * *Regional:* {$this->account->rgn_id}
                        * *Primer Nombre:* {$this->account->primer_nombre}
                        * *Segundo Nombre:* {$this->account->segundo_nombre}
                        * *Primer Apellido:* {$this->account->primer_apellido}
                        * *Segundo Apellido:* {$this->account->segundo_apellido}
                        * *Usuario:* {$this->account->usuario}
                    ",
                'type' => 1,
                'status' => 1,
                'urgency' => 4,
                'impact' => 3,
                'requesttypes_id' => 1,
                'groups_id' => 5, // ID del grupo asignado
                'users_id_assign' => 10, // ID del técnico asignado

            ]
        ];
    }

    private function nemotecniaTemplateContractor(): array
    {
        return [
            'input' => [
                'name' => "Caso por Nemotecnia - Contratista (Fallido)",
                'content' => "
                        *Datos del Usuario:*
                        * *Regional:* {$this->account->rgn_id}
                        * *Primer Nombre:* {$this->account->primer_nombre}
                        * *Segundo Nombre:* {$this->account->segundo_nombre}
                        * *Primer Apellido:* {$this->account->primer_apellido}
                        * *Segundo Apellido:* {$this->account->segundo_apellido}
                        * *Correo Personal:* {$this->account->correo_personal}
                        * *Correo Electrónico Institucional:* {$this->account->correo_institucional}
                        * *Número de Contrato SECOP II:* {$this->account->numero_contrato}
                        * *Fecha de Inicio del Contrato:* {$this->account->fecha_inicio_contrato}
                        * *Fecha de Terminación del Contrato:* {$this->account->fecha_terminacion_contrato}
                        * *Usuario:* {$this->account->usuario}
                    ",
                'type' => 1,
                'status' => 1,
                'urgency' => 4,
                'impact' => 3,
                'requesttypes_id' => 1,
                'groups_id' => 5, // ID del grupo asignado
                'users_id_assign' => 10, // ID del técnico asignado

            ]
        ];

    }
    private function nemotecniaTemplaFun(): array
    {
        return [
            'input' => [
                'name' => "Caso por Nemotecnia - Funcionario (Fallido)",
                'content' => "
                        *Datos del Usuario:*
                        * *Regional:* {$this->account->rgn_id}
                        * *Primer Nombre:* {$this->account->primer_nombre}
                        * *Segundo Nombre:* {$this->account->segundo_nombre}
                        * *Primer Apellido:* {$this->account->primer_apellido}
                        * *Segundo Apellido:* {$this->account->segundo_apellido}
                        * *Correo Personal:* {$this->account->correo_personal}
                        * *Correo Electrónico Institucional:* {$this->account->correo_institucional}
                        * *Número de Contrato SECOP II:* {$this->account->numero_contrato}
                        * *Fecha de Inicio del Contrato:* {$this->account->fecha_inicio_contrato}
                        * *Fecha de Terminación del Contrato:* {$this->account->fecha_terminacion_contrato}
                        * *Usuario:* {$this->account->usuario}
                    ",
                'type' => 1,
                'status' => 1,
                'urgency' => 4,
                'impact' => 3,
                'requesttypes_id' => 1,
                'groups_id' => 5, // ID del grupo asignado
                'users_id_assign' => 10, // ID del técnico asignado

            ]
        ];
    }
    private function secopTemplate(): array
    {
        return [
            'input' => [
                'name' => "Caso por SECOP RECHAZADO",
                'content' => "
                            *Datos del Usuario:*
                            * *Regional:* {$this->account->rgn_id}
                            * *Primer Nombre:* {$this->account->primer_nombre}
                            * *Segundo Nombre:* {$this->account->segundo_nombre}
                            * *Primer Apellido:* {$this->account->primer_apellido}
                            * *Segundo Apellido:* {$this->account->segundo_apellido}
                            * *Usuario:* {$this->account->usuario}
                        ",
                'type' => 1,
                'status' => 1,
                'urgency' => 4,
                'impact' => 3,
                'requesttypes_id' => 1,
                'groups_id' => 5, // ID del grupo asignado
                'users_id_assign' => 10, // ID del técnico asignado

            ]
        ];

    }
}
