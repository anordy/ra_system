<?php

namespace Database\Seeders;

use App\Models\FinancialYear;
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
        $years = ['2021','2022'];
        foreach($years as $year){
            FinancialYear::query()->updateOrCreate([
                'name'=>$year
            ]);
        }
    }
}
