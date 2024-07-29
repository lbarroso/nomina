<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class SalariesTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        DB::table('salaries')->insert([
            ['id' => 1, 'puesto' => 'CHOFER GASOLINA', 'tab_ant'=>'7654.09', 'tab_vig'=>'8800.00', 'uma_ant'=>'108.57','uma_vig'=>'108.57'],
            ['id' => 2, 'puesto' => 'CHOFER DIESEL', 'tab_ant'=>'7922.07', 'tab_vig'=>'9300.00', 'uma_ant'=>'108.57','uma_vig'=>'108.57'],
            ['id' => 3, 'puesto' => 'CHOFER TORTHON', 'tab_ant'=>'8564.42', 'tab_vig'=>'9300.00', 'uma_ant'=>'108.57','uma_vig'=>'108.57'],
            ['id' => 4, 'puesto' => 'OPERADOR TIENDA MOVIL', 'tab_ant'=>'7857.42', 'tab_vig'=>'9300.00', 'uma_ant'=>'108.57','uma_vig'=>'108.57'],
            ['id' => 5, 'puesto' => 'LOTEADOR', 'tab_ant'=>'6596.59', 'tab_vig'=>'7800.00', 'uma_ant'=>'108.57','uma_vig'=>'108.57'],
            ['id' => 6, 'puesto' => 'VELADOR', 'tab_ant'=>'6814.88', 'tab_vig'=>'7567.47', 'uma_ant'=>'108.57','uma_vig'=>'108.57'],
            ['id' => 7, 'puesto' => 'AUXILIAR ADMINISTRATIVO', 'tab_ant'=>'7435.47', 'tab_vig'=>'8000.00', 'uma_ant'=>'108.57','uma_vig'=>'108.57'],
            ['id' => 8, 'puesto' => 'AUXILIAR OPERATIVO', 'tab_ant'=>'7024.09', 'tab_vig'=>'7567.47', 'uma_ant'=>'108.57','uma_vig'=>'108.57'],
            ['id' => 9, 'puesto' => 'MONTACARGUISTA', 'tab_ant'=>'7332.04', 'tab_vig'=>'7800.00', 'uma_ant'=>'108.57','uma_vig'=>'108.57'],
            ['id' => 10, 'puesto' => 'OPERADOR DE LANCHA', 'tab_ant'=>'8564.42', 'tab_vig'=>'8800.00', 'uma_ant'=>'108.57','uma_vig'=>'108.57'],
            ['id' => 11, 'puesto' => 'PROMOTOR COMUNITARIO', 'tab_ant'=>'8564.42', 'tab_vig'=>'9300.00', 'uma_ant'=>'108.57','uma_vig'=>'108.57']
        ]);

    }
}
