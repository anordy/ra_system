<?php

namespace Database\Seeders;

use App\Models\AccountType;
use Illuminate\Database\Seeder;

class AccountTypesSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        AccountType::updateOrCreate([
            'short_name' => 'current',
            'name' => 'Current Account'
        ]);
        AccountType::updateOrCreate([
            'short_name' => 'savings',
            'name' => 'Savings Account'
        ]);
        AccountType::updateOrCreate([
            'short_name' => 'salary',
            'name' => 'Salary Account'
        ]);
        AccountType::updateOrCreate([
            'short_name' => 'fixed-deposit',
            'name' => 'Fixed Deposit Account'
        ]);
        AccountType::updateOrCreate([
            'short_name' => 'recurring-deposit',
            'name' => 'Reccuring Deposit Account'
        ]);
    }
}
