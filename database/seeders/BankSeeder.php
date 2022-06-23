<?php

namespace Database\Seeders;

use App\Models\Bank;
use Illuminate\Database\Seeder;

class BankSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $data = [
            ['name' => 'CRDB'],
            ['name' => 'NMB'],
            ['name' => 'AZANIA'],
        ];
        foreach ($data as $row) {
            Bank::updateOrCreate($row);
        }
    }
}
