<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Services\RetryValidationService;
use App\Services\SendValidationStatusService;
use App\Services\AccountTicketService;

class RetryValidation extends Command
{
    protected $signature = 'app:retry-validation';
    protected $description = 'Reintenta las validaciones y marca tickets como solucionados';

    public function __construct()
    {
        parent::__construct();
    }

    public function handle(): void
    {
        $retryService = new RetryValidationService(
            app(SendValidationStatusService::class),
            app(AccountTicketService::class)
        );

        $retryService->retry();
    }
}

