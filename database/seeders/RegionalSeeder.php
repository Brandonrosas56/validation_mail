<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;

class RegionalSeeder extends Seeder
{
    public function run()
    {
        DB::table('regional')->insert([
            [
                'rgn_id' => 1,
                'rgn_nombre' => 'Región 1',
                'rgn_direccion' => 'Dirección 1',
                'pai_id' => 1,
                'pai_nombre' => 'País 1',
                'dpt_id' => 1,
                'dpt_nombre' => 'Departamento 1',
                'mpo_id' => 1,
                'mpo_nombre' => 'Municipio 1',
                'zon_id' => 1,
                'zon_nombre' => 'Zona 1',
                'bar_id' => 1,
                'bar_nombre' => 'Barrio 1',
                'rgn_fch_registro' => Carbon::now(),
                'rgn_estado' => 1,
            ],
            [
                'rgn_id' => 2,
                'rgn_nombre' => 'Región 2',
                'rgn_direccion' => 'Dirección 2',
                'pai_id' => 2,
                'pai_nombre' => 'País 2',
                'dpt_id' => 2,
                'dpt_nombre' => 'Departamento 2',
                'mpo_id' => 2,
                'mpo_nombre' => 'Municipio 2',
                'zon_id' => 2,
                'zon_nombre' => 'Zona 2',
                'bar_id' => 2,
                'bar_nombre' => 'Barrio 2',
                'rgn_fch_registro' => Carbon::now(),
                'rgn_estado' => 1,
            ],
            // Añade más registros según sea necesario
        ]);
    }
}
