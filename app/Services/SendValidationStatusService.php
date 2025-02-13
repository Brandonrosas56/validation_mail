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
    private GLPIService $GLPIService;

    public function __construct(array $userData, string $state)
    {
        $this->userData = $userData;
        $this->state = $state;
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
                $this->GLPIService->createTicket($this->nemotecniaTemplateContractor());
                break;

            case self::RECJECTED_ERROR:
                // Usar la plantilla nemotecniaTemplateContractor
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
                'type' => 2,
                'status' => 1,
                'urgency' => 3,
                'impact' => 3,
                'requesttypes_id' => 1,
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
                'type' => 2,
                'status' => 1,
                'urgency' => 3,
                'impact' => 3,
                'requesttypes_id' => 1,
            ]
        ];

    }
    private function nemotecniaTemplaFun(): array
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
                'type' => 2,
                'status' => 1,
                'urgency' => 3,
                'impact' => 3,
                'requesttypes_id' => 1,
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
                'type' => 3,
                'status' => 3,
                'urgency' => 3,
                'impact' => 3,
                'requesttypes_id' => 1,
            ]
        ];

    }
}
