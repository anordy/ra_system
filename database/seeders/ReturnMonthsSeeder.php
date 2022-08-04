<?php

namespace Database\Seeders;

use App\Models\FinancialYear;
use App\Models\ReturnMonth;
use Illuminate\Database\Seeder;

class ReturnMonthsSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {

        $months2022 =
            [
                ['name' => 'January', 'code' => 'J1', 'start_date' => '2022-01-01', 'due_date' => '2022-01-23'],
                ['name' => 'February', 'code' => 'F2', 'start_date' => '2022-02-01', 'due_date' => '2022-02-23'],
                ['name' => 'March', 'code' => 'M3', 'start_date' => '2022-03-01', 'due_date' => '2022-03-23'],
                ['name' => 'April', 'code' => 'A4', 'start_date' => '2022-04-01', 'due_date' => '2022-04-23'],
                ['name' => 'May', 'code' => 'M5', 'start_date' => '2022-05-01', 'due_date' => '2022-05-23'],
                ['name' => 'June', 'code' => 'J6', 'start_date' => '2022-06-01', 'due_date' => '2022-06-23'],
                ['name' => 'July', 'code' => 'J7', 'start_date' => '2022-07-01', 'due_date' => '2022-07-23'],
                ['name' => 'August', 'code' => 'A8', 'start_date' => '2022-08-01', 'due_date' => '2022-08-23'],
                ['name' => 'September', 'code' => 'S9', 'start_date' => '2022-09-01', 'due_date' => '2022-09-23'],
                ['name' => 'October', 'code' => 'O10', 'start_date' => '2022-10-01', 'due_date' => '2022-10-23'],
                ['name' => 'November', 'code' => 'N11', 'start_date' => '2022-11-01', 'due_date' => '2022-11-23'],
                ['name' => 'December', 'code' => 'D12', 'start_date' => '2022-12-01', 'due_date' => '2022-12-23']
            ];

        $year = FinancialYear::query()->where('name', '2022')->first();
        foreach ($months2022 as $x => $months) {
            ReturnMonth::query()->updateOrCreate([
                'name' => $months['name'],
                'code' => $months['code'],
                'start_date' => $months['start_date'],
                'due_date' => $months['due_date'],
                'financial_year_id' => $year->id,
            ]);
        }


    }
}
