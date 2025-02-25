<?php

namespace Database\Seeders;

use App\Models\Currency;
use App\Models\System;
use Illuminate\Database\Seeder;

class CurrencySeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $data = [
            ['name' => 'TZS', 'code' => 'TZS','country' => 'Tanzania'],
            ['name' => 'USD', 'code' => 'USD','country' => 'America'],
            ['name' => 'GBP', 'code' => 'GBP','country' => 'British'],
        ];

        foreach ($data as $row) {
            Currency::updateOrCreate($row);
        }
    }
}
