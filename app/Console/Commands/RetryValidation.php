<?php

namespace App\Console\Commands;

use App\Models\CreateAccount;
use App\Models\ValidateAccount;
use App\Services\SecopService;
use App\Services\SendValidationStatusService;
use Illuminate\Console\Command;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\Log;
class RetryValidation extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'app:retry-validation';

    /**
     * The console command description.
     *
     * @var string
     */
     protected $description = 'Reintenta la validación de contratos en SECOP dentro de las 48 horas';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $this->verifyAccounts(CreateAccount::class);
        $this->verifyAccounts(ValidateAccount::class);
    }

    private function verifyAccounts(string $modelClass): void
    {
        $accounts = $modelClass::where('intentos_validacion', '>', 0)->get();
        foreach ($accounts as $account) {
            if (!SecopService::isValidSecopContract($account->documento_proveedor, $account->numero_contrato)) {
                $account->intentos_validacion -= 1;
                $account->save();
                
                if ($account->intentos_validacion == 0 || ($account->pending_sent_at && Carbon::parse($account->pending_sent_at)->diffInHours(now()) >= 48)) {
                    (new SendValidationStatusService($account, SendValidationStatusService::TEMPLATE_REJECTED))->sendTicket();
                    Log::info("Plantilla de rechazo enviada después de 48 horas para la cuenta ID: {$account->id}");
                }
            }
        }
    }
}
