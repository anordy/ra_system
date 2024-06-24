<?php

namespace Database\Seeders;

use App\Models\Reports\Report;
use App\Models\Reports\ReportType;
use App\Models\ZrbBankAccount;
use Illuminate\Database\Seeder;

class ReportTypesSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $types = [
            ['name' => 'TAX PAYER REGISTRATION'],
            ['name' => 'RETURNS'],
        ];

        foreach ($types as $type) {
            ReportType::updateOrCreate($type);
        }
    }
}
