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
        $months2021 =
            [
                ['name'=>'January', 'code'=>'J1', 'due_date'=>'2022-01-23'],
                ['name'=>'February', 'code'=>'F2', 'due_date'=>'2022-02-23'],
                ['name'=>'March', 'code'=>'M3', 'due_date'=>'2022-03-23'],
                ['name'=>'April', 'code'=>'A4', 'due_date'=>'2022-04-23'],
                ['name'=>'May', 'code'=>'M5', 'due_date'=>'2022-05-23'],
                ['name'=>'June', 'code'=>'J6', 'due_date'=>'2022-06-23'],
                ['name'=>'July', 'code'=>'J7', 'due_date'=>'2022-07-23'],
                ['name'=>'August', 'code'=>'A8', 'due_date'=>'2022-08-23'],
                ['name'=>'September', 'code'=>'S9', 'due_date'=>'2022-09-23'],
                ['name'=>'October', 'code'=>'O10', 'due_date'=>'2022-10-23'],
                ['name'=>'November', 'code'=>'N11', 'due_date'=>'2022-11-23'],
                ['name'=>'December', 'code'=>'D12', 'due_date'=>'2022-12-23']
            ];

        $year = FinancialYear::query()->where('name','2021')->first();
        foreach($months2021 as $x =>$months){
            ReturnMonth::query()->updateOrCreate([
                'name'=> $months['name'],
                'code'=>$months['code'],
                'due_date'=>$months['due_date'],
                'financial_year_id' => $year->id,
            ]);
        }

        $months2022 =
            [
                ['name'=>'January', 'code'=>'J1', 'due_date'=>'2022-01-20'],
                ['name'=>'February', 'code'=>'F2', 'due_date'=>'2022-02-20'],
                ['name'=>'March', 'code'=>'M3', 'due_date'=>'2022-03-20'],
                ['name'=>'April', 'code'=>'A4', 'due_date'=>'2022-04-20'],
                ['name'=>'May', 'code'=>'M5', 'due_date'=>'2022-05-20'],
                ['name'=>'June', 'code'=>'J6', 'due_date'=>'2022-06-20'],
                ['name'=>'July', 'code'=>'J7', 'due_date'=>'2022-07-20'],
                ['name'=>'August', 'code'=>'A8', 'due_date'=>'2022-08-20'],
                ['name'=>'September', 'code'=>'S9', 'due_date'=>'2022-09-20'],
                ['name'=>'October', 'code'=>'O10', 'due_date'=>'2022-10-20'],
                ['name'=>'November', 'code'=>'N11', 'due_date'=>'2022-11-20'],
                ['name'=>'December', 'code'=>'D12', 'due_date'=>'2022-12-20']
            ];

        $year = FinancialYear::query()->where('name','2022')->first();
        foreach($months2022 as $x =>$months){
            ReturnMonth::query()->updateOrCreate([
                'name'=> $months['name'],
                'code'=>$months['code'],
                'due_date'=>$months['due_date'],
                'financial_year_id' => $year->id,
            ]);
        }



    }
}
