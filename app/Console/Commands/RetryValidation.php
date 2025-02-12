<?php

namespace App\Console\Commands;

use App\Models\User;
use App\Services\SendValidationStatusService;
use Illuminate\Console\Command;
use Faker\Factory as Faker;

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


        $faker = Faker::create();

        
}
}