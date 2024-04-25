<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class DepartmentsTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $departments = [
            ['code' => 'LTD', 'prefix' => '01', 'name' => 'LTD','status' => 'active'],
            ['code' => 'DTD', 'prefix' => '02', 'name' => 'DTD','status' => 'active'],
            ['code' => 'NTRD', 'prefix' => '03', 'name' => 'NTRD','status' => 'active'],
            ['code' => 'PEMBA', 'prefix' => '04', 'name' => 'PEMBA','status' => 'active'],
        ];

        foreach ($departments as $department) {
            DB::table('tax_departments')->insert($department);
        }
    }
}
