<?php

namespace App\Console\Commands;

use App\Models\User;
use Faker\Factory as Faker;
use App\Models\CreateAccount;
use App\Services\SecopService;
use App\Models\ValidateAccount;
use Illuminate\Console\Command;
use App\Services\SendValidationStatusService;

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
    protected $description = 'Command description';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $this->verifyCreatedAccounts();
        $this->verifyValidatedAccounts();
    }

    private function verifyCreatedAccounts(): bool
    {
        $CreateAccounts = CreateAccount::where('intentos_validacion', '>', 0)->get();
        foreach ($CreateAccounts as $CreateAccount) {
            if (!SecopService::isValidSecopContract($CreateAccount->documento_proveedor, $CreateAccount->numero_contrato)) {
                $CreateAccount->getService()->failureValidation();
                if ($CreateAccount->intentos_validacion == 0) {
                    $CreateAccount->getService()->changeStatus(CreateAccount::REVISION);
                }
            }
        }
        return true;
    }
    private function verifyValidatedAccounts(): bool
    {
        $ValidateAccounts = ValidateAccount::where('intentos_validacion', '>', 0)->get();
        foreach ($ValidateAccounts as $ValidateAccount) {
            if (!SecopService::isValidSecopContract($ValidateAccount->documento_proveedor, $ValidateAccount->numero_contrato)) {
                $ValidateAccount->getService()->failureValidation();
                if ($ValidateAccount->intentos_validacion == 0) {
                    $ValidateAccount->getService()->changeStatus(CreateAccount::REVISION);
                }
            }
        }
        return true;
    }
}