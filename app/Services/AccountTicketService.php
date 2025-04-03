<?php

namespace App\Services;

use App\Models\User;
use GuzzleHttp\Client;
use App\Models\AccountTicket;
use App\Models\CreateAccount;
use App\Models\ValidateAccount;
use GuzzleHttp\Exception\RequestException;
use Illuminate\Support\Facades\Log;

class AccountTicketService
{
    protected $Model;
    protected $glpiService;

    /**
     * Constructor de la clase.
     *
     * @param AccountTicket $Model - Instancia del modelo de ticket de cuenta
     * @param GLPIService $glpiService - Instancia del servicio GLPI
     */
    public function __construct($Model, GLPIService $glpiService)
    {
        $this->Model = $Model;
        $this->glpiService = $glpiService;
    }

    /**
     * Crea un nuevo ticket asociado a una cuenta de usuario.
     *
     * @param CreateAccount|ValidateAccount $account La cuenta asociada al ticket.
     * @param array $ticketInfo Información del ticket.
     * @param string $typeTicket El tipo de ticket (CREATE o VALIDATION).
     * @return AccountTicket|null
     */
    public function create(CreateAccount|ValidateAccount $account, array $ticketInfo, string $typeTicket): ?AccountTicket
    {
        try {
            Log::info('Creando ticket en la base de datos local', [
                'account_id' => $account->id,
                'ticket_info' => $ticketInfo,
                'type_ticket' => $typeTicket
            ]);

            $typeAccount = $account instanceof CreateAccount ? CreateAccount::CREATE_ACCOUNT : CreateAccount::VALIDATE_ACCOUNT;

            if (!isset($ticketInfo['id'])) {
                Log::error('El ticket no tiene ID', ['ticket_info' => $ticketInfo]);
                return null;
            }

            // Preparar los datos del ticket
            $data = [
                'account_id' => $account->id,
                'type_account' => $typeAccount,
                'type_ticket' => $typeTicket,
                'ticket_id' => $ticketInfo['id'],
                'ticket_info' => $ticketInfo,
                'ticket_state' => $ticketInfo['status'] ?? 'Pendiente',
            ];

            Log::info('Datos del ticket a crear', $data);

            // Crear el ticket usando el método create del modelo
            $ticket = AccountTicket::create($data);

            if (!$ticket) {
                Log::error('Error al crear el ticket en la base de datos');
                return null;
            }

            Log::info('Ticket creado exitosamente', [
                'ticket_id' => $ticket->ticket_id,
                'account_id' => $ticket->account_id,
                'type_account' => $ticket->type_account,
                'type_ticket' => $ticket->type_ticket
            ]);

            return $ticket;
        } catch (\Exception $e) {
            Log::error('Error al crear ticket en la base de datos', [
                'error' => $e->getMessage(),
                'account_id' => $account->id,
                'ticket_info' => $ticketInfo,
                'type_ticket' => $typeTicket,
                'trace' => $e->getTraceAsString()
            ]);
            return null;
        }
    }

    /**
     * Obtiene la cuenta asociada a un ticket de cuenta.
     *
     * @return CreateAccount|ValidateAccount - Retorna la cuenta de usuario asociada
     */
    public function getAccount(): CreateAccount|ValidateAccount
    {
        return $this->isCreateAccount() 
            ? CreateAccount::find($this->getModel()->account_id) 
            : ValidateAccount::find($this->getModel()->account_id);
    }

    /**
     * Verifica si el ticket pertenece a una cuenta de creación.
     *
     * @return bool - Retorna true si la cuenta es de tipo creación, false si es de validación
     */
    function isCreateAccount(): bool
    {
        return $this->getModel()->type_account == CreateAccount::CREATE_ACCOUNT;
    }

    /**
     * Actualiza la información del ticket de cuenta.
     *
     * @param array $ticketInfo - Información actualizada del ticket
     * @return bool - Retorna true si la actualización fue exitosa
     */
    public function updateTicketInfo($ticketInfo): bool
    {
        $data = [
            'ticket_info' => json_encode($ticketInfo),
            'ticket_state' => $ticketInfo['status'],
        ];
        $this->getModel()->update($data);
        return true;
    }

    /**
     * Obtiene la instancia del modelo de ticket de cuenta.
     *
     * @return AccountTicket - Retorna el modelo de ticket asociado
     */
    public function getModel(): AccountTicket
    {
        return $this->Model;
    }
}