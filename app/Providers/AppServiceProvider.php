<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use Illuminate\Support\Facades\URL;
use App\Services\AccountTicketService;
use App\Models\AccountTicket;
use App\Services\GLPIService;
use App\Services\SendValidationStatusService;
use App\Models\CreateAccount;


class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        $this->app->singleton(AccountTicketService::class, function ($app) {
            return new AccountTicketService(
                $app->make(AccountTicket::class),
                $app->make(GLPIService::class)
            );
        });

        $this->app->singleton(SendValidationStatusService::class, function ($app) {
            return new SendValidationStatusService(
                $app->make(CreateAccount::class),
                '',
                $app->make(GLPIService::class),
                $app->make(AccountTicketService::class)
            );
        });
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        if ($this->app->environment('production')) {
            URL::forceScheme('https');
        }
    }
}