<?php

namespace Database\Seeders;

use App\Models\FinancialMonth;
use App\Models\FinancialYear;
use Carbon\Carbon;
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
        $years = [
            ['name' => '2021/2022', 'code' => '2021'],
            ['name' => '2022/2023', 'code' => '2022'],
            ['name' => '2023/2024', 'code' => '2023'],
        ];

        $months = [
            1 => 'January',
            2 => 'February',
            3 => 'March',
            4 => 'April',
            5 => 'May',
            6 => 'June',
            7 => 'July',
            8 => 'August',
            9 => 'September',
            10 => 'October',
            11 => 'November',
            12 => 'December'
        ];

        foreach($years as $year){
            $yr = FinancialYear::query()->updateOrCreate($year);

            foreach($months as $index => $month){
                FinancialMonth::create([
                    'financial_year_id' => $yr->id,
                    'number' => $index,
                    'name' => $month,
                    'due_date' => Carbon::create($year['code'], $index, 20)->toDateTimeString()
                ]);
            }
        }

    }
}
