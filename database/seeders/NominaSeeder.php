<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Nomina;
use Illuminate\Support\Facades\DB;
use Faker\Factory as Faker;

class NominaSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $faker = Faker::create();

        // Insertar 100 registros en la tabla 'nominas'
        for ($i = 0; $i < 100; $i++) {
            Nomina::create([
                'almcnt' => $faker->numberBetween(1, 10), // Almacén aleatorio
                'semana' => $faker->numberBetween(1, 52), // Semana aleatoria
                'mes' => $faker->numberBetween(1, 12), // Mes aleatorio
                'year' => $faker->numberBetween(2020, 2024), // Año aleatorio
                'motivo' => $faker->sentence, // Motivo aleatorio
                'periodicidad' => $faker->randomElement(['semanal', 'especial', 'extraordinaria', 'extemporanea']),
                'tipo_nomina' => $faker->randomElement(['regular', 'extraordinaria', 'extemporanea']),
                'fechaInicio' => $faker->date('Y-m-d'), // Fecha de inicio aleatoria
                'fechaFin' => $faker->date('Y-m-d'), // Fecha de fin aleatoria
                'fechaPago' => $faker->date('Y-m-d'), // Fecha de pago aleatoria
                'diasPagados' => $faker->numberBetween(1, 7), // Días pagados aleatorios
                'status' => $faker->randomElement(['abierta', 'cerrada']), // Estado aleatorio
            ]);
        }
    }
}
