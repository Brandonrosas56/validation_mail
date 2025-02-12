<?php

namespace App\Console\Commands;

use App\Models\User;
use Illuminate\Console\Command;

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
        $User = User::find(1);
        print_r('Diana estas viendo esto desde un comando '.$User->name.' '.$User->email);
    }
}
