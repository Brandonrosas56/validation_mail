<?php

namespace App\Services;

use App\Models\CreateAccount;
use App\Models\ValidateAccount;
use Illuminate\Support\Facades\DB;
use Log;

class RetryValidationService
{
    private SendValidationStatusService $sendValidationStatusService;
    private AccountTicketService $accountTicketService;
    private GLPIService $glpiService;

    public function __construct(SendValidationStatusService $sendValidationStatusService, AccountTicketService $accountTicketService)
    {
        $this->sendValidationStatusService = $sendValidationStatusService;
        $this->accountTicketService = $accountTicketService;
        $this->glpiService = app(GLPIService::class);
    }

    public function retry(): void
    {
        // Consulta original tal cual me la diste
        $accounts = DB::select("
            SELECT at2.account_id, ca.estado, at2.type_account, at2.ticket_id , at2.type_ticket 
            FROM public.create_account ca 
            INNER JOIN account_tickets at2 
            ON ca.id = at2.account_id 
            WHERE ca.estado IN ('exito', 'rechazo', 'revision') 
            AND at2.type_ticket IN ('SUCCESS_CONTRACTOR', 'PENDING_CONTRACTOR', 'PENDING_FUNCTIONARY')and at2.type_account ='1'
            UNION ALL
            SELECT at2.account_id, va.estado, at2.type_account, at2.ticket_id , at2.type_ticket 
            FROM public.validate_account va 
            INNER JOIN account_tickets at2 
            ON va.id = at2.account_id 
            WHERE va.estado IN ('exito', 'rechazo', 'revision') 
            AND at2.type_ticket IN ('SUCCESS_CONTRACTOR', 'PENDING_CONTRACTOR', 'PENDING_FUNCTIONARY') and at2.type_account ='2';
        ");

        foreach ($accounts as $account) {
            Log::info('Procesando validación', [
                'account_id' => $account->account_id,
                'estado' => $account->estado,
                'ticket_id' => $account->ticket_id,
            ]);

            // Detectar el modelo
            $model = $account->type_account == CreateAccount::CREATE_ACCOUNT ? CreateAccount::find($account->account_id) : ValidateAccount::find($account->account_id);

            if (!$model) {
                Log::warning('No se encontró la cuenta ni en create_account ni en validate_account', [
                    'account_id' => $account->account_id,
                ]);
                continue;
            }

            // Enviar plantilla según estado actual
            $this->sendTemplateByEstado($model, $account->estado);

            // Mapear estado
            $nuevoEstado = $this->mapEstado($account->estado);

            // Verificar si es necesario actualizar
            if ($model->estado !== $nuevoEstado) {
                Log::info('Actualizando estado', [
                    'anterior' => $model->estado,
                    'nuevo' => $nuevoEstado
                ]);
                $model->estado = $nuevoEstado;
                $model->save();
            } else {
                Log::info('El estado ya estaba actualizado', ['estado_actual' => $model->estado]);
            }

            // Marcar ticket como resuelto (o lo que tu GLPI permita)
            if ($account->ticket_id) {
                $this->glpiService->markTicketAsSolved($account->ticket_id);
                Log::info('Ticket marcado como solucionado', ['ticket_id' => $account->ticket_id]);
            }
        }
    }

    private function sendTemplateByEstado($model, string $estado): void
    {
        match ($estado) {
            'exito' => $this->sendTicket($model, SendValidationStatusService::TEMPLATE_SUCCESS_CONTRACTOR_CREACION_CLOSE),
            'rechazo' => $this->sendTicket($model, SendValidationStatusService::TEMPLATE_REJECTED_FUNCTIONARY_ACTIVACION),
            'revision' => $this->sendTicket($model, SendValidationStatusService::TEMPLATE_REVIEW_ACTIVACION_CREATION),
            default => Log::info('Estado sin plantilla', ['estado' => $estado]),
        };
    }

    private function sendTicket($model, string $template): void
    {
        $service = new SendValidationStatusService(
            $model,
            $template,
            $this->glpiService,
            $this->accountTicketService
        );
        $service->sendTicket();
    }

    private function mapEstado(string $estado): string
    {
        return match ($estado) {
            'exito' => 'exitoso',
            'rechazo' => 'rechazado',
            'revision' => 'en_revision',
            default => $estado,
        };
    }
}
