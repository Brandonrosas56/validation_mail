<?php

namespace App\Services;

use GuzzleHttp\Client;
use GuzzleHttp\Exception\RequestException;

class SendValidationStatusService
{
    private const TEMPLATE_SUCCESS = 'success';
    private const NEMOTECNIA_TEMPLATE = 'nemotecnia';
    const NEMOTECNIA_ERROR = 'ERRRO_NEMOTECNIA';

    const NEMOTECNIA_ERROR_FUN = 'NEMOTECNIA_ERROR_FUN';
    const RECJECTED_ERROR = 'RECJECTED_ERROR';
    const VALIDATION_SUCCESS = 'VALIDATION_OK';
    private array $userData = [];
    private string $state = '';
    private bool $contractor = false;
    private GLPIService $GLPIService;

    public function __construct(array $userData, string $state, $contractor = false)
    {
        $this->userData = $userData;
        $this->state = $state;
        $this->contractor = $contractor;
        $this->GLPIService = new GLPIService();
    }

    public function sendTicket(): void
    {
        switch ($this->state) {
            case self::VALIDATION_SUCCESS:
                // Usar la plantilla successTemplate
                $this->GLPIService->createTicket($this->successTemplate());
                break;

            case self::NEMOTECNIA_ERROR:
                // Usar la plantilla nemotecniaTemplateContractor
                //dd($this->contractor);
                $template = $this->contractor ? $this->nemotecniaTemplateContractor() : $this->nemotecniaTemplaFun();
                $this->GLPIService->createTicket($template);
                break;

            case self::RECJECTED_ERROR:
                // Usar la plantilla nemotecniaTemplateContractor
                dd('Entrando en rejectedTemplate', $this->rejectedTemplate());
                $this->GLPIService->createTicket($this->rejectedTemplate());
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
                        * *Regional:* {$this->userData['rgn_id']}
                        * *Primer Nombre:* {$this->userData['primer_nombre']}
                        * *Segundo Nombre:* {$this->userData['segundo_nombre']}
                        * *Primer Apellido:* {$this->userData['primer_apellido']}
                        * *Segundo Apellido:* {$this->userData['segundo_apellido']}
                        * *Usuario:* {$this->userData['usuario']}
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
                        * *Regional:* {$this->userData['rgn_id']}
                        * *Primer Nombre:* {$this->userData['primer_nombre']}
                        * *Segundo Nombre:* {$this->userData['segundo_nombre']}
                        * *Primer Apellido:* {$this->userData['primer_apellido']}
                        * *Segundo Apellido:* {$this->userData['segundo_apellido']}
                        * *Correo Personal:* {$this->userData['correo_personal']}
                        * *Correo Electrónico Institucional:* {$this->userData['correo_institucional']}
                        * *Número de Contrato SECOP II:* {$this->userData['numero_contrato']}
                        * *Fecha de Inicio del Contrato:* {$this->userData['fecha_inicio_contrato']}
                        * *Fecha de Terminación del Contrato:* {$this->userData['fecha_terminacion_contrato']}
                        * *Usuario:* {$this->userData['usuario']}
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
                        * *Regional:* {$this->userData['rgn_id']}
                        * *Primer Nombre:* {$this->userData['primer_nombre']}
                        * *Segundo Nombre:* {$this->userData['segundo_nombre']}
                        * *Primer Apellido:* {$this->userData['primer_apellido']}
                        * *Segundo Apellido:* {$this->userData['segundo_apellido']}
                        * *Correo Personal:* {$this->userData['correo_personal']}
                        * *Correo Electrónico Institucional:* {$this->userData['correo_institucional']}
                        * *Número de Contrato SECOP II:* {$this->userData['numero_contrato']}
                        * *Fecha de Inicio del Contrato:* {$this->userData['fecha_inicio_contrato']}
                        * *Fecha de Terminación del Contrato:* {$this->userData['fecha_terminacion_contrato']}
                        * *Usuario:* {$this->userData['usuario']}
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
    private function rejectedTemplate(): array
    {
        return [
            'input' => [
                'name' => "Caso por Nemotecnia - Contratista (Fallido)",
                'content' => "
                            *Datos del Usuario:*
                            * *Regional:* {$this->userData['rgn_id']}
                            * *Primer Nombre:* {$this->userData['primer_nombre']}
                            * *Segundo Nombre:* {$this->userData['segundo_nombre']}
                            * *Primer Apellido:* {$this->userData['primer_apellido']}
                            * *Segundo Apellido:* {$this->userData['segundo_apellido']}
                            * *Usuario:* {$this->userData['usuario']}
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
