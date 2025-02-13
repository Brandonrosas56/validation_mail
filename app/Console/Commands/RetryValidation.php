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
        $userData = [
            'rgn_id' => $faker->numberBetween(1, 10), // ID regional (puedes ajustar el rango)
            'primer_nombre' => $faker->firstName,
            'segundo_nombre' => $faker->firstName,
            'primer_apellido' => $faker->lastName,
            'segundo_apellido' => $faker->lastName,
            'correo_personal' => $faker->safeEmail,
            'correo_institucional' => $faker->unique()->companyEmail,
            'numero_contrato' => $faker->regexify('SECOP-\d{6}'), // Contrato con un patrón específico
            'fecha_inicio_contrato' => $faker->date('Y-m-d', 'now'),
            'fecha_terminacion_contrato' => $faker->date('Y-m-d', '+1 year'),
            'usuario' => $faker->userName,
        ];

     $sendemailValidationStatusService = new SendValidationStatusService($userData, SendValidationStatusService::NEMOTECNIA_ERROR
    );
    $sendemailValidationStatusService->sendTicket();

        
}
}