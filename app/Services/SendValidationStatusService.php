<?php

namespace App\Services;

use App\Models\AccountTicket;
use GuzzleHttp\Client;
use App\Models\CreateAccount;
use GuzzleHttp\Exception\RequestException;

class SendValidationStatusService
{
    private const TEMPLATE_SUCCESS = 'success';
    const SECOP_ERROR = 'SECOP_ERROR';
    const NEMOTECNIA_ERROR = 'ERRRO_NEMOTECNIA';

    const NEMOTECNIA_ERROR_FUN = 'NEMOTECNIA_ERROR_FUN';
    const RECJECTED_ERROR = 'RECJECTED_ERROR';
    const VALIDATION_SUCCESS = 'VALIDATION_OK';
    private CreateAccount $createAccount;
    private string $state = '';
    private bool $contractor = false;
    private GLPIService $GLPIService;

    public function __construct(CreateAccount $createAccount, string $state, $contractor = false)
    {
        $this->createAccount = $createAccount;
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
                $ticketInfo = $this->GLPIService->getTicketInfo($response['id']);
                AccountTicketService::create($this->createAccount, $ticketInfo);
                break;
            case self::NEMOTECNIA_ERROR:
                $template = $this->contractor ? $this->nemotecniaTemplateContractor() : $this->nemotecniaTemplaFun();
                $response = $this->GLPIService->createTicket($template);
                $ticketInfo = $this->GLPIService->getTicketInfo($response['id']);
                AccountTicketService::create($this->createAccount, $ticketInfo);
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
                        * *Regional:* {$this->createAccount->rgn_id}
                        * *Primer Nombre:* {$this->createAccount->primer_nombre}
                        * *Segundo Nombre:* {$this->createAccount->segundo_nombre}
                        * *Primer Apellido:* {$this->createAccount->primer_apellido}
                        * *Segundo Apellido:* {$this->createAccount->segundo_apellido}
                        * *Usuario:* {$this->createAccount->usuario}
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
                        * *Regional:* {$this->createAccount->rgn_id}
                        * *Primer Nombre:* {$this->createAccount->primer_nombre}
                        * *Segundo Nombre:* {$this->createAccount->segundo_nombre}
                        * *Primer Apellido:* {$this->createAccount->primer_apellido}
                        * *Segundo Apellido:* {$this->createAccount->segundo_apellido}
                        * *Correo Personal:* {$this->createAccount->correo_personal}
                        * *Correo Electrónico Institucional:* {$this->createAccount->correo_institucional}
                        * *Número de Contrato SECOP II:* {$this->createAccount->numero_contrato}
                        * *Fecha de Inicio del Contrato:* {$this->createAccount->fecha_inicio_contrato}
                        * *Fecha de Terminación del Contrato:* {$this->createAccount->fecha_terminacion_contrato}
                        * *Usuario:* {$this->createAccount->usuario}
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
                        * *Regional:* {$this->createAccount->rgn_id}
                        * *Primer Nombre:* {$this->createAccount->primer_nombre}
                        * *Segundo Nombre:* {$this->createAccount->segundo_nombre}
                        * *Primer Apellido:* {$this->createAccount->primer_apellido}
                        * *Segundo Apellido:* {$this->createAccount->segundo_apellido}
                        * *Correo Personal:* {$this->createAccount->correo_personal}
                        * *Correo Electrónico Institucional:* {$this->createAccount->correo_institucional}
                        * *Número de Contrato SECOP II:* {$this->createAccount->numero_contrato}
                        * *Fecha de Inicio del Contrato:* {$this->createAccount->fecha_inicio_contrato}
                        * *Fecha de Terminación del Contrato:* {$this->createAccount->fecha_terminacion_contrato}
                        * *Usuario:* {$this->createAccount->usuario}
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
                            * *Regional:* {$this->createAccount->rgn_id}
                            * *Primer Nombre:* {$this->createAccount->primer_nombre}
                            * *Segundo Nombre:* {$this->createAccount->segundo_nombre}
                            * *Primer Apellido:* {$this->createAccount->primer_apellido}
                            * *Segundo Apellido:* {$this->createAccount->segundo_apellido}
                            * *Usuario:* {$this->createAccount->usuario}
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
