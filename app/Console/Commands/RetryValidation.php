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

        $data = [
            'rgn_id' => $faker->randomNumber(4, true), // Un número de 4 dígitos
            'primer_nombre' => $faker->firstName,
            'segundo_nombre' => $faker->optional()->firstName,
            'primer_apellido' => $faker->lastName,
            'segundo_apellido' => $faker->optional()->lastName,
            'documento_proveedor' => $faker->optional()->numerify('DOC-#####'), // Valor opcional, formato personalizado
            'correo_personal' => $faker->unique()->safeEmail,
            'correo_institucional' => $faker->unique()->safeEmail,
            'numero_contrato' => $faker->numerify('CONTRATO-###'), // Valor de contrato con formato
            'fecha_inicio_contrato' => $faker->date($format = 'Y-m-d', $max = 'now'), // Fecha antes de hoy
            'fecha_terminacion_contrato' => $faker->date($format = 'Y-m-d', $min = 'now'), 
            'usuario' => 'usuario de prueba'// Fecha desde hoy en adelante
        ];

        $SendValidationStatusService = new SendValidationStatusService($data,SendValidationStatusService::NEMOTECNIA_ERROR);
        $SendValidationStatusService->sendTicket();
    }
}
