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
    // Plantillas de Creación
    public const TEMPLATE_PENDING_FUNCTIONARY_CREACION = 'PENDING_FUNCTIONARY_CREACION';
    public const TEMPLATE_PENDING_CONTRACTOR_CREACION = 'PENDING_CONTRACTOR_CREACION';
    public const TEMPLATE_SUCCESS_FUNCTIONARY_CREACION = 'SUCCESS_FUNCTIONARY_CREACION';
    public const TEMPLATE_SUCCESS_CONTRACTOR_CREACION = 'SUCCESS_CONTRACTOR_CREACION';
    public const TEMPLATE_SUCCESS_CONTRACTOR_CREACION_CLOSE = 'SUCCESS_CONTRACTOR_CREACION_CLOSE';
    public const TEMPLATE_REJECTED_FUNCTIONARY_CREACION = 'REJECTED_FUNCTIONARY_CREACION';
    public const TEMPLATE_REJECTED_CONTRACTOR_CREACION = 'REJECTED_CONTRACTOR_CREACION';

    // Plantillas de Activación
    public const TEMPLATE_PENDING_FUNCTIONARY_ACTIVACION = 'PENDING_FUNCTIONARY_ACTIVACION';
    public const TEMPLATE_PENDING_CONTRACTOR_ACTIVACION = 'PENDING_CONTRACTOR_ACTIVACION';
    public const TEMPLATE_SUCCESS_CONTRACTOR_ACTIVACION = 'SUCCESS_CONTRACTOR_ACTIVACION';
    public const TEMPLATE_SUCCESS_CONTRACTOR_ACTIVACION_CLOSE = 'TEMPLATE_SUCCESS_CONTRACTOR_ACTIVACION_CLOSE';
    public const TEMPLATE_SUCCESS_FUNCTIONARY_ACTIVACION = 'SUCCESS_FUNCTIONARY_ACTIVACION';
    public const TEMPLATE_REJECTED_FUNCTIONARY_ACTIVACION = 'REJECTED_FUNCTIONARY_ACTIVACION';
    public const TEMPLATE_REJECTED_CONTRACTOR_ACTIVACION = 'REJECTED_CONTRACTOR_ACTIVACION';



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
        try {
            switch ($this->state) {
                case self::TEMPLATE_PENDING_FUNCTIONARY_CREACION:
                    $response = $this->GLPIService->createTicket($this->pendingFunctionaryCreationTemplate());
                    Log::info('Plantilla de pendiente enviada', ['ticket' => $response]);
                    break;
                case self::TEMPLATE_PENDING_CONTRACTOR_CREACION:
                    $response = $this->GLPIService->createTicket($this->pendingContractorCreationTemplate());
                    Log::info('Plantilla de pendiente enviada', ['ticket' => $response]);
                    break;
                case self::TEMPLATE_SUCCESS_FUNCTIONARY_CREACION:
                    $response = $this->GLPIService->createTicket($this->successFunctionaryCreationTemplate());
                    
                    Log::info('Plantilla de éxito para creación de funcionario enviada', ['ticket' => $response]);
                    break;
                case self::TEMPLATE_SUCCESS_CONTRACTOR_CREACION:
                    $response = $this->GLPIService->createTicket($this->successContractorCreationTemplate());
                
                    Log::info('Plantilla de éxito para creación de contratista enviada', ['ticket' => $response]);
                    break;
                case self::TEMPLATE_SUCCESS_CONTRACTOR_CREACION_CLOSE:
                        $response = $this->GLPIService->createTicket($this->successContractorCreationTemplate_close());
                
                        Log::info('Plantilla de éxito para creación de contratista enviada', ['ticket' => $response]);
                        break;
                case self::TEMPLATE_REJECTED_CONTRACTOR_CREACION:
                    $response = $this->GLPIService->createTicket($this->rejectedContractorCreationTemplate());
                    
                    Log::info('Plantilla de rechazo para creación de contratista enviada', ['ticket' => $response]);
                    break;
                    case self::TEMPLATE_REJECTED_FUNCTIONARY_CREACION:
                        $response = $this->GLPIService->createTicket($this->rejectedFuncionaryCreationTemplate());
                     
                        Log::info('Plantilla de rechazo para creación de contratista enviada', ['ticket' => $response]);
                        break;
                case self::TEMPLATE_PENDING_FUNCTIONARY_ACTIVACION:
                    $response = $this->GLPIService->createTicket($this->pendingFunctionaryActivationTemplate());
                    Log::info('Plantilla de pendiente de validación de funcionario enviada', ['ticket' => $response]);
                    break;
                case self::TEMPLATE_PENDING_CONTRACTOR_ACTIVACION:
                    $response = $this->GLPIService->createTicket($this->pendingContractorActivationTemplate());
                    Log::info('Plantilla de pendiente de validación de contratista enviada', ['ticket' => $response]);
                    break;
                case self::TEMPLATE_SUCCESS_FUNCTIONARY_ACTIVACION:
                    $response = $this->GLPIService->createTicket($this->successFunctionaryActivationTemplate());
                   
                    Log::info('Plantilla de éxito para activación de funcionario enviada', ['ticket' => $response]);
                    break;
                case self::TEMPLATE_SUCCESS_CONTRACTOR_ACTIVACION:
                    $response = $this->GLPIService->createTicket($this->successContractorActivationTemplate());
                 
                    Log::info('Plantilla de éxito para activación de contratista enviada', ['ticket' => $response]);
                    break;
                case self::TEMPLATE_SUCCESS_CONTRACTOR_ACTIVACION_CLOSE:
                        $response = $this->GLPIService->createTicket($this->successContractorActivationTemplate_close());
                 
                        Log::info('Plantilla de éxito para activación de contratista enviada', ['ticket' => $response]);
                        break;
                case self::TEMPLATE_REJECTED_FUNCTIONARY_ACTIVACION:
                    $response = $this->GLPIService->createTicket($this->rejectedFunctionaryActivationTemplate());
                
                    Log::info('Plantilla de rechazo para activación de funcionario enviada', ['ticket' => $response]);
                    break;
                case self::TEMPLATE_REJECTED_CONTRACTOR_ACTIVACION:
                    $response = $this->GLPIService->createTicket($this->rejectedContractorActivationTemplate());
                  
                    Log::info('Plantilla de rechazo para activación de contratista enviada', ['ticket' => $response]);
                    break;
                default:
                    Log::warning('Estado de plantilla no reconocido: ' . $this->state);
            }
        } catch (\Exception $e) {
            Log::error('Error al enviar el ticket: ' . $e->getMessage());
        }
    }


    private function getUserInfo(): array
    {
        $user = auth()->user();
        
        // Asegúrate que este método realmente devuelve el ID correcto de GLPI
        $glpiID = $this->GLPIService->getGlpiID(); 
        
        if (!$glpiID) {
            throw new \Exception('No se pudo obtener el glpiID del usuario.');
        }
        
        return [
            'email' => $user->email ?? 'No disponible',
            'document' => $user->supplier_document ?? 'No disponible',
            'group_id' => $user->glpi_group_id ?? null,
            'user_id' => $user->glpi_user_id ?? null,
            'glpiID' => $glpiID,
        ];
    }
      

    // Métodos para Plantillas de Creación
    private function pendingFunctionaryCreationTemplate(): array
    {
        $userInfo = $this->getUserInfo();
        Log::info('Información plantilla del usuario', $userInfo);

        return [
            'input' => [
                'name' => "Validación exitosa - Solicitud de creación de correo para FUNCIONARIO",
                'content' => "La validación de los datos fue exitosa, Por favor, completar proceso de creacion de correo para el usuario FUNCIONARIO con los siguientes datos:"
                    . "\n**Datos del Usuario:**"
                    . "\n- Regional: {$this->account->rgn_id}"
                    . "\n- Nombre: {$this->account->primer_nombre} {$this->account->segundo_nombre}"
                    . "\n- Apellido: {$this->account->primer_apellido} {$this->account->segundo_apellido}"
                    . "\n- Documento: {$this->account->documento_proveedor}"
                    . "\n- Correo personal: {$this->account->correo_personal}"
                    . "\n- Relacion Contratual: {$this->account->rol_asignado}"
                    . "\n- Fecha Inicio contrato: {$this->account->fecha_inicio_contrato}"
                    . "\n- Numero de acta: {$this->account->numero_contrato}"

                    . "\n\n**Datos del Solicitante:**"
                    . "\n- Correo: {$userInfo['email']}"
                    . "\n- Documento: {$userInfo['document']}",
                    'type' => 1,
                    'status' => 2, // "En curso"
                    'urgency' => 4,
                    'impact' => 3,
                    'requesttypes_id' => 1,
                    'groups_id' => 1,
                    '_groups_id_assign' => 2,
                    '_users_id_assign' => 2,
                    'itilcategories_id' => 1,
                    '_users_id_requester' => $userInfo['glpiID'], 
                     'users_id_assign' => $userInfo['user_id'],
            ]
        ];
    }

    private function pendingContractorCreationTemplate(): array
    {
        $userInfo = $this->getUserInfo();
        Log::info('Información del usuario:', $userInfo);

        return [
            'input' => [
                'name' => "Validación contrato SECOP en curso - Solicitud de creación de correo para CONTRATISTA",
                'content' => "Se está validando la información del usuario en SECOP para la creación de su correo con los siguientes datos."
                . "\n -Datos del Usuario:"
                . "\n- Regional: {$this->account->rgn_id}"
                . "\n- Tipo de documento: {$this->account->tipo_documento}"
                . "\n- Documento de identidad: {$this->account->documento_proveedor}"
                . "\n- Nombre: {$this->account->primer_nombre} {$this->account->segundo_nombre}"
                . "\n- Apellido: {$this->account->primer_apellido} {$this->account->segundo_apellido}"
                . "\n- Relación Contractual: {$this->account->rol_asignado}"
                . "\n- Correo Electronico Personal: {$this->account->correo_personal}"
                . "\n- Fecha de Inicio del Contrato: {$this->account->fecha_inicio_contrato}"
                . "\n- Fecha de Terminación del Contrato: {$this->account->fecha_terminacion_contrato}"
                . "\n- Número de Contrato: {$this->account->numero_contrato}"

                . "\n\nDatos del Solicitante:"
                . "\n- Correo: {$userInfo['email']}"
                . "\n- Documento: {$userInfo['document']}",
                'type' => 1,
                'status' => 2, // "En curso"
                'urgency' => 4,
                'impact' => 3,
                'requesttypes_id' => 1,
                'groups_id' => 1,
                '_groups_id_assign' => 2,
                '_users_id_assign' => 2,
                'itilcategories_id' => 1,
                '_users_id_requester' => $userInfo['glpiID'], 
                 'users_id_assign' => $userInfo['user_id'],
            ]
        ];
    }
    private function successContractorCreationTemplate(): array
    {
        $userInfo = $this->getUserInfo();
        Log::info('Información plantilla de validacion exitosa secop y paso para creación de correo CONTRATISTA', $userInfo);

        return [
            'input' => [
                'name' => "Validación exitosa SECOP - Solicitud de creación de correo de CONTRATISTA",
                'content' => "La validación de los datos en el secop fue exitosa y el usuario CONTRATISTA ha sido creado correctamente para su creación de correo con los siguientes datos:"
                    . "\nDatos del Usuario:"
                    . "\n- Regional: {$this->account->rgn_id}"
                    . "\n- Tipo de documento: {$this->account->tipo_documento}"
                    . "\n- Documento de identidad: {$this->account->documento_proveedor}"
                    . "\n- Nombre: {$this->account->primer_nombre} {$this->account->segundo_nombre}"
                    . "\n- Apellido: {$this->account->primer_apellido} {$this->account->segundo_apellido}"
                    . "\n- Relación Contractual: {$this->account->rol_asignado}"
                    . "\n- Correo Electronico Personal: {$this->account->correo_personal}"
                    . "\n- Fecha de Inicio del Contrato: {$this->account->fecha_inicio_contrato}"
                    . "\n- Fecha de Terminación del Contrato: {$this->account->fecha_terminacion_contrato}"
                    . "\n- Número de Contrato: {$this->account->numero_contrato}"

                    . "\n\nDatos del Solicitante:"
                    . "\n- Correo: {$userInfo['email']}"
                    . "\n- Documento: {$userInfo['document']}",
                    'type' => 1,
                    'status' => 2, // "En curso"
                    'urgency' => 4,
                    'impact' => 3,
                    'requesttypes_id' => 1,
                    'groups_id' => 1,
                    '_groups_id_assign' => 2,
                    '_users_id_assign' => 2,
                    'itilcategories_id' => 1,
                    '_users_id_requester' => $userInfo['glpiID'], 
                     'users_id_assign' => $userInfo['user_id'],
            ]
        ];
    }


    private function successFunctionaryCreationTemplate(): array
    {
        $userInfo = $this->getUserInfo();
        Log::info('Información plantilla de cierre exitoso para creación de FUNCIONARIO', $userInfo);

        return [
            'input' => [
                'name' => "Cierre de Caso - Validación y Creación Exitosa",
                'content' => "La validación de los datos fue exitosa y se completó la creación del correo de FUNCIONARIO"
                    . "\nDatos del Usuario:"
                    . "\n- Regional: {$this->account->rgn_id}"
                    . "\n- Tipo de documento: {$this->account->tipo_documento}"
                    . "\n- Documento de identidad: {$this->account->documento_proveedor}"
                    . "\n- Nombre: {$this->account->primer_nombre} {$this->account->segundo_nombre}"
                    . "\n- Apellido: {$this->account->primer_apellido} {$this->account->segundo_apellido}"
                    . "\n- Relación Contractual: {$this->account->rol_asignado}"
                    . "\n- Correo Electronico Personal: {$this->account->correo_personal}"
                    . "\n- Fecha de Inicio del Contrato: {$this->account->fecha_inicio_contrato}"
                    . "\n- Fecha de Terminación del Contrato: {$this->account->fecha_terminacion_contrato}"
                    . "\n- Número de Contrato: {$this->account->numero_contrato}"

                    . "\n\nDatos del Solicitante:"
                    . "\n- Correo: {$userInfo['email']}"
                    . "\n- Documento: {$userInfo['document']}",
                    'type' => 1,
                    'status' => 2, // "En curso"
                    'urgency' => 4,
                    'impact' => 3,
                    'requesttypes_id' => 1,
                    'groups_id' => 1,
                    '_groups_id_assign' => 2,
                    '_users_id_assign' => 2,
                    'itilcategories_id' => 1,
                    '_users_id_requester' => $userInfo['glpiID'], 
                     'users_id_assign' => $userInfo['user_id'],
            ]
        ];
    }
    private function successContractorCreationTemplate_close(): array
    {
        $userInfo = $this->getUserInfo();
        Log::info('Información plantilla de cierre exitoso para creación de FUNCIONARIO', $userInfo);

        return [
            'input' => [
                'name' => "Cierre de Caso - Creacion-Validación y Creación Exitosa",
                'content' => "La validación de los datos fue exitosa y se completó la creación del correo "
                    . "\nDatos del Usuario:"
                    . "\n- Regional: {$this->account->rgn_id}"
                    . "\n- Tipo de documento: {$this->account->tipo_documento}"
                    . "\n- Documento de identidad: {$this->account->documento_proveedor}"
                    . "\n- Nombre: {$this->account->primer_nombre} {$this->account->segundo_nombre}"
                    . "\n- Apellido: {$this->account->primer_apellido} {$this->account->segundo_apellido}"
                    . "\n- Relación Contractual: {$this->account->rol_asignado}"
                    . "\n- Correo Electronico Personal: {$this->account->correo_personal}"
                    . "\n- Fecha de Inicio del Contrato: {$this->account->fecha_inicio_contrato}"
                    . "\n- Fecha de Terminación del Contrato: {$this->account->fecha_terminacion_contrato}"
                    . "\n- Número de Contrato: {$this->account->numero_contrato}"

                    . "\n\nDatos del Solicitante:"
                    . "\n- Correo: {$userInfo['email']}"
                    . "\n- Documento: {$userInfo['document']}",
                    'type' => 1,
                    'status' => 2, // "En curso"
                    'urgency' => 4,
                    'impact' => 3,
                    'requesttypes_id' => 1,
                    'groups_id' => 1,
                    '_groups_id_assign' => 2,
                    '_users_id_assign' => 2,
                    'itilcategories_id' => 1,
                    '_users_id_requester' => $userInfo['glpiID'], 
                     'users_id_assign' => $userInfo['user_id'],
            ]
        ];
    }
    
    private function rejectedContractorCreationTemplate(): array
    {
        $userInfo = $this->getUserInfo();
        Log::info('Información plantilla de rechazo para creación de CONTRATISTA', $userInfo);

        return [
            'input' => [
                'name' => "Cierre de Caso - Fallo en la Validación de Datos para creación de correo CONTRATISTA ",
                'content' => "No se logró validar la nemotecnia del usuario tras los intentos correspondientes."
                . "\nDatos del Usuario:"
                . "\n- Regional: {$this->account->rgn_id}"
                . "\n- Tipo de documento: {$this->account->tipo_documento}"
                . "\n- Documento de identidad: {$this->account->documento_proveedor}"
                . "\n- Nombre: {$this->account->primer_nombre} {$this->account->segundo_nombre}"
                . "\n- Apellido: {$this->account->primer_apellido} {$this->account->segundo_apellido}"
                . "\n- Relación Contractual: {$this->account->rol_asignado}"
                . "\n- Correo Electronico Personal: {$this->account->correo_personal}"
                . "\n- Fecha de Inicio del Contrato: {$this->account->fecha_inicio_contrato}"
                . "\n- Fecha de Terminación del Contrato: {$this->account->fecha_terminacion_contrato}"
                . "\n- Número de Contrato: {$this->account->numero_contrato}"

                . "\n\nDatos del Solicitante:"
                . "\n- Correo: {$userInfo['email']}"
                . "\n- Documento: {$userInfo['document']}",
                'type' => 1,
                'status' => 2, // "En curso"
                'urgency' => 4,
                'impact' => 3,
                'requesttypes_id' => 1,
                'groups_id' => 1,
                '_groups_id_assign' => 2,
                '_users_id_assign' => 2,
                'itilcategories_id' => 1,
                '_users_id_requester' => $userInfo['glpiID'], 
                 'users_id_assign' => $userInfo['user_id'],
            ]
        ];
    }
    private function rejectedFuncionaryCreationTemplate(): array
    {
        $userInfo = $this->getUserInfo();
        Log::info('Información plantilla de rechazo para creación de CONTRATISTA', $userInfo);

        return [
            'input' => [
                'name' => "Cierre de Caso - Fallo en la Validación de Datos para creación de correo FUNCIONARIO",
                'content' => "No se logró validar la nemotecnia del usuario tras los intentos correspondientes."
                . "\nDatos del Usuario:"
                . "\n- Regional: {$this->account->rgn_id}"
                . "\n- Tipo de documento: {$this->account->tipo_documento}"
                . "\n- Documento de identidad: {$this->account->documento_proveedor}"
                . "\n- Nombre: {$this->account->primer_nombre} {$this->account->segundo_nombre}"
                . "\n- Apellido: {$this->account->primer_apellido} {$this->account->segundo_apellido}"
                . "\n- Relación Contractual: {$this->account->rol_asignado}"
                . "\n- Correo Electronico Personal: {$this->account->correo_personal}"
                . "\n- Fecha de Inicio del Contrato: {$this->account->fecha_inicio_contrato}"
                . "\n- Fecha de Terminación del Contrato: {$this->account->fecha_terminacion_contrato}"
                . "\n- Número de Contrato: {$this->account->numero_contrato}"

                . "\n\nDatos del Solicitante:"
                . "\n- Correo: {$userInfo['email']}"
                . "\n- Documento: {$userInfo['document']}",
                'type' => 1,
                'status' => 2, // "En curso"
                'urgency' => 4,
                'impact' => 3,
                'requesttypes_id' => 1,
                'groups_id' => 1,
                '_groups_id_assign' => 2,
                '_users_id_assign' => 2,
                'itilcategories_id' => 1,
                '_users_id_requester' => $userInfo['glpiID'], 
                 'users_id_assign' => $userInfo['user_id'],
            ]
        ];
    }



    // Métodos para Plantillas de Activación
    private function pendingFunctionaryActivationTemplate(): array
    {
        $userInfo = $this->getUserInfo();
        Log::info('Información plantilla de funcionario', $userInfo);

        return [
            'input' => [
                'name' => "Validación exitosa - Solicitud de activación correo de FUNCIONARIO",
                'content' => "La información del usuario ha sido validada exitosamente, por favor realizar la activación del correo FUNCIONARIO"
                . "\n**Datos del Usuario:**"
                . "\n- Regional: {$this->account->rgn_id}"
                . "\n- Nombre: {$this->account->primer_nombre} {$this->account->segundo_nombre}"
                . "\n- Apellido: {$this->account->primer_apellido} {$this->account->segundo_apellido}"
                . "\n- Documento: {$this->account->documento_proveedor}"
                . "\n- Correo personal: {$this->account->correo_personal}"
                . "\n- Relacion Contratual: {$this->account->rol_asignado}"
                . "\n- Fecha Inicio contrato: {$this->account->fecha_inicio_contrato}"
                . "\n- Numero de acta: {$this->account->numero_contrato}"
                . "\n- Usuario: {$this->account->usuario}"

                . "\n\n**Datos del Solicitante:**"
                . "\n- Correo: {$userInfo['email']}"
                . "\n- Documento: {$userInfo['document']}",
                'type' => 1,
                'status' => 2, // "En curso"
                'urgency' => 4,
                'impact' => 3,
                'requesttypes_id' => 1,
                'groups_id' => 1,
                '_groups_id_assign' => 2,
                '_users_id_assign' => 2,
                'itilcategories_id' => 1,
                '_users_id_requester' => $userInfo['glpiID'], 
                 'users_id_assign' => $userInfo['user_id'],
            ]
        ];
    }

    private function pendingContractorActivationTemplate(): array
    {
        $userInfo = $this->getUserInfo();
        Log::info('Información plantilla de contratista', $userInfo);

        return [
            'input' => [
                'name' => "Validación en curso SECOP - Solicitud Activacion correo CONTRATISTA",
                'content' => "Se está validando la información del contratista en SECOP para su posterior activación de correo."
                  . "\n**Datos del Usuario:**"
                . "\n- Regional: {$this->account->rgn_id}"
                . "\n- Tipo de documento: {$this->account->tipo_documento}"
                . "\n- Documento de identidad: {$this->account->documento_proveedor}"
                . "\n- Nombre: {$this->account->primer_nombre} {$this->account->segundo_nombre}"
                . "\n- Apellido: {$this->account->primer_apellido} {$this->account->segundo_apellido}"
                . "\n- Relación Contractual: {$this->account->rol_asignado}"
                . "\n- Correo Electronico Personal: {$this->account->correo_personal}"
                . "\n- Correo Electronico Institucional: {$this->account->correo_institucional}"
                . "\n- Fecha de Inicio del Contrato: {$this->account->fecha_inicio_contrato}"
                . "\n- Fecha de Terminación del Contrato: {$this->account->fecha_terminacion_contrato}"
                . "\n- Número de Contrato: {$this->account->numero_contrato}"
                . "\n- Usuario: {$this->account->usuario}"

                . "\n\nDatos del Solicitante:"
                . "\n- Correo: {$userInfo['email']}"
                . "\n- Documento: {$userInfo['document']}",   
                'type' => 1,
                'status' => 2, // "En curso"
                'urgency' => 4,
                'impact' => 3,
                'requesttypes_id' => 1,
                'groups_id' => 1,
                '_groups_id_assign' => 2,
                '_users_id_assign' => 2,
                'itilcategories_id' => 1,
                '_users_id_requester' => $userInfo['glpiID'], 
                 'users_id_assign' => $userInfo['user_id'],
            ]
        ];
    }
    private function successContractorActivationTemplate(): array
    {
        $userInfo = $this->getUserInfo();
        Log::info('Información plantilla de cierre exitoso para activación de contratista', $userInfo);

        return [
            'input' => [
                'name' => "Validacion SECOP exitosa - Solicitud Activacion correo de CONTRATISTA",
                'content' => "La validación de los datos en el secop fue exitosa y el usuario CONTRATISTA ha sido creado correctamente para su activacion de correo con los siguientes datos:"
                  . "\nDatos del Usuario:"
                    . "\n- Regional: {$this->account->rgn_id}"
                    . "\n- Tipo de documento: {$this->account->tipo_documento}"
                    . "\n- Documento de identidad: {$this->account->documento_proveedor}"
                    . "\n- Nombre: {$this->account->primer_nombre} {$this->account->segundo_nombre}"
                    . "\n- Apellido: {$this->account->primer_apellido} {$this->account->segundo_apellido}"
                    . "\n- Relación Contractual: {$this->account->rol_asignado}"
                    . "\n- Correo Electronico Personal: {$this->account->correo_personal}"
                    . "\n- Correo Electronico Institucional: {$this->account->correo_institucional}"
                    . "\n- Fecha de Inicio del Contrato: {$this->account->fecha_inicio_contrato}"
                    . "\n- Fecha de Terminación del Contrato: {$this->account->fecha_terminacion_contrato}"
                    . "\n- Número de Contrato: {$this->account->numero_contrato}"
                    . "\n- Usuario: {$this->account->usuario}"

                    . "\n\nDatos del Solicitante:"
                    . "\n- Correo: {$userInfo['email']}"
                    . "\n- Documento: {$userInfo['document']}",    
                    'type' => 1,
                    'status' => 2, // "En curso"
                    'urgency' => 4,
                    'impact' => 3,
                    'requesttypes_id' => 1,
                    'groups_id' => 1,
                    '_groups_id_assign' => 2,
                    '_users_id_assign' => 2,
                    'itilcategories_id' => 1,
                    '_users_id_requester' => $userInfo['glpiID'], 
                     'users_id_assign' => $userInfo['user_id'],
            ]
        ];
    }

    private function successFunctionaryActivationTemplate(): array
    {
        $userInfo = $this->getUserInfo();
        Log::info('Información plantilla de cierre exitoso para activación de funcionario', $userInfo);

        return [
            'input' => [
                'name' => ": Cierre de Caso - Validación y Activación Exitosa FUNCIONARIO",
                'content' => "La validación de los datos fue exitosa y se completó la activación del FUNCIONARIO."
                . "\nDatos del Usuario:"
                . "\n- Regional: {$this->account->rgn_id}"
                . "\n- Tipo de documento: {$this->account->tipo_documento}"
                . "\n- Documento de identidad: {$this->account->documento_proveedor}"
                . "\n- Nombre: {$this->account->primer_nombre} {$this->account->segundo_nombre}"
                . "\n- Apellido: {$this->account->primer_apellido} {$this->account->segundo_apellido}"
                . "\n- Relación Contractual: {$this->account->rol_asignado}"
                . "\n- Correo Electronico Personal: {$this->account->correo_personal}"
                . "\n- Correo Electronico Institucional: {$this->account->correo_institucional}"
                . "\n- Fecha de Inicio del Contrato: {$this->account->fecha_inicio_contrato}"
                . "\n- Fecha de Terminación del Contrato: {$this->account->fecha_terminacion_contrato}"
                . "\n- Número de Contrato: {$this->account->numero_contrato}"
                . "\n- Usuario: {$this->account->usuario}"

                . "\n\nDatos del Solicitante:"
                . "\n- Correo: {$userInfo['email']}"
                . "\n- Documento: {$userInfo['document']}",  
                'type' => 1,
                'status' => 2, // "En curso"
                'urgency' => 4,
                'impact' => 3,
                'requesttypes_id' => 1,
                'groups_id' => 1,
                '_groups_id_assign' => 2,
                '_users_id_assign' => 2,
                'itilcategories_id' => 1,
                '_users_id_requester' => $userInfo['glpiID'], 
                 'users_id_assign' => $userInfo['user_id'],
            ]
        ];
    }
    private function successContractorActivationTemplate_close(): array
    {
        $userInfo = $this->getUserInfo();
        Log::info('Información plantilla de cierre exitoso para activación de funcionario', $userInfo);

        return [
            'input' => [
                'name' => "Cierre de Caso - Validación y Activación Exitosa CONTRATISTA",
                'content' => "La validación de los datos fue exitosa y se completó la activación del CONTRATISTA"
                . "\nDatos del Usuario:"
                . "\n- Regional: {$this->account->rgn_id}"
                . "\n- Tipo de documento: {$this->account->tipo_documento}"
                . "\n- Documento de identidad: {$this->account->documento_proveedor}"
                . "\n- Nombre: {$this->account->primer_nombre} {$this->account->segundo_nombre}"
                . "\n- Apellido: {$this->account->primer_apellido} {$this->account->segundo_apellido}"
                . "\n- Relación Contractual: {$this->account->rol_asignado}"
                . "\n- Correo Electronico Personal: {$this->account->correo_personal}"
                . "\n- Correo Electronico Institucional: {$this->account->correo_institucional}"
                . "\n- Fecha de Inicio del Contrato: {$this->account->fecha_inicio_contrato}"
                . "\n- Fecha de Terminación del Contrato: {$this->account->fecha_terminacion_contrato}"
                . "\n- Número de Contrato: {$this->account->numero_contrato}"
                . "\n- Usuario: {$this->account->usuario}"

                . "\n\nDatos del Solicitante:"
                . "\n- Correo: {$userInfo['email']}"
                . "\n- Documento: {$userInfo['document']}",  
                'type' => 1,
                'status' => 2, // "En curso"
                'urgency' => 4,
                'impact' => 3,
                'requesttypes_id' => 1,
                'groups_id' => 1,
                '_groups_id_assign' => 2,
                '_users_id_assign' => 2,
                'itilcategories_id' => 1,
                '_users_id_requester' => $userInfo['glpiID'], 
                 'users_id_assign' => $userInfo['user_id'],
            ]
        ];
    }


  
    private function rejectedFunctionaryActivationTemplate(): array
    {
        $userInfo = $this->getUserInfo();
        Log::info('Información plantilla de rechazo para creacion-activación ', $userInfo);

        return [
            'input' => [
                'name' => "Cierre de Caso - Fallo en la Validación de Datos para activación de correo ",
                'content' => "No se logró validar la nemotecnia del usuario tras los intentos correspondientes."
                . "\nDatos del Usuario:"
                . "\n- Regional: {$this->account->rgn_id}"
                . "\n- Tipo de documento: {$this->account->tipo_documento}"
                . "\n- Documento de identidad: {$this->account->documento_proveedor}"
                . "\n- Nombre: {$this->account->primer_nombre} {$this->account->segundo_nombre}"
                . "\n- Apellido: {$this->account->primer_apellido} {$this->account->segundo_apellido}"
                . "\n- Relación Contractual: {$this->account->rol_asignado}"
                . "\n- Correo Electronico Personal: {$this->account->correo_personal}"
                . "\n- Correo Electronico Institucional: {$this->account->correo_institucional}"
                . "\n- Fecha de Inicio del Contrato: {$this->account->fecha_inicio_contrato}"
                . "\n- Fecha de Terminación del Contrato: {$this->account->fecha_terminacion_contrato}"
                . "\n- Número de Contrato: {$this->account->numero_contrato}"
                . "\n- Usuario: {$this->account->usuario}"

                . "\n\nDatos del Solicitante:"
                . "\n- Correo: {$userInfo['email']}"
                . "\n- Documento: {$userInfo['document']}",    
                'type' => 1,
                'status' => 2, // "En curso"
                'urgency' => 4,
                'impact' => 3,
                'requesttypes_id' => 1,
                'groups_id' => 1,
                '_groups_id_assign' => 2,
                '_users_id_assign' => 2,
                'itilcategories_id' => 1,
                '_users_id_requester' => $userInfo['glpiID'], 
                 'users_id_assign' => $userInfo['user_id'],
            ]
        ];
    }

    private function rejectedContractorActivationTemplate(): array
    {
        $userInfo = $this->getUserInfo();
        Log::info('Información plantilla de rechazo para activación de contratista', $userInfo);

        return [
            'input' => [
                'name' => "Hola estoy entrando al cierre rechazado correctamente Cierre de Caso - Fallo en la Validación de Datos para activación de correo FUNCIONARIO",
                'content' => "No se logró validar la nemotecnia del usuario tras los intentos correspondientes."
                . "\nDatos del Usuario:"
                . "\n- Regional: {$this->account->rgn_id}"
                . "\n- Tipo de documento: {$this->account->tipo_documento}"
                . "\n- Documento de identidad: {$this->account->documento_proveedor}"
                . "\n- Nombre: {$this->account->primer_nombre} {$this->account->segundo_nombre}"
                . "\n- Apellido: {$this->account->primer_apellido} {$this->account->segundo_apellido}"
                . "\n- Relación Contractual: {$this->account->rol_asignado}"
                . "\n- Correo Electronico Personal: {$this->account->correo_personal}"
                . "\n- Correo Electronico Institucional: {$this->account->correo_institucional}"
                . "\n- Fecha de Inicio del Contrato: {$this->account->fecha_inicio_contrato}"
                . "\n- Fecha de Terminación del Contrato: {$this->account->fecha_terminacion_contrato}"
                . "\n- Número de Contrato: {$this->account->numero_contrato}"
                . "\n- Usuario: {$this->account->usuario}"

                . "\n\nDatos del Solicitante:"
                . "\n- Correo: {$userInfo['email']}"
                . "\n- Documento: {$userInfo['document']}",    
                'type' => 1,
                'status' => 2, // "En curso"
                'urgency' => 4,
                'impact' => 3,
                'requesttypes_id' => 1,
                'groups_id' => 1,
                '_groups_id_assign' => 2,
                '_users_id_assign' => 2,
                'itilcategories_id' => 1,
                '_users_id_requester' => $userInfo['glpiID'], 
                 'users_id_assign' => $userInfo['user_id'],
                
            ]
        ];
    }
    

}