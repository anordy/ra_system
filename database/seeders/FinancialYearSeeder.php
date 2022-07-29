<?php

namespace Database\Seeders;

use App\Models\Returns\FinancialYear;
use Illuminate\Database\Seeder;

class FinancialYearSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        FinancialYear::create([
            'code' => '2022',
            'name' => "2021/2022"
        ]);
    }
}
