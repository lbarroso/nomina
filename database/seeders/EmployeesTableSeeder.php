<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class EmployeesTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        DB::table('employees')->insert([
            ['almcnt'=> 2010, 'expediente'=>9532, 'nombre' => 'luis rey', 'salary_id' => '1'],
            ['almcnt'=> 2010, 'expediente'=>1, 'nombre' => 'diego', 'salary_id' => '2'],
            ['almcnt'=> 2010, 'expediente'=>2, 'nombre' => 'alfredo', 'salary_id' => '3']
        ]);

    }
}
