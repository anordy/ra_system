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
        AccountType::create([
            'short_name' => 'current',
            'name' => 'Current Account'
        ]);
        AccountType::create([
            'short_name' => 'savings',
            'name' => 'Current Account'
        ]);
        AccountType::create([
            'short_name' => 'salary',
            'name' => 'Current Account'
        ]);
        AccountType::create([
            'short_name' => 'fixed-deposit',
            'name' => 'Current Account'
        ]);
        AccountType::create([
            'short_name' => 'recurring-deposit',
            'name' => 'Current Account'
        ]);
    }
}
