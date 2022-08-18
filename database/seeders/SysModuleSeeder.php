<?php

namespace Database\Seeders;

use App\Models\SysModule;
use Illuminate\Database\Seeder;

class SysModuleSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $data = [
            ['id' => 1, 'code' => 'taxpayer-management' ,'name' => 'Taxpayer Management'],
            ['id' => 2, 'code' => 'tax-consultant', 'name' => 'Tax Consultant'],
            ['id' => 3, 'code' => 'upgrade-tax-types', 'name' => 'UpgradTax Types'],
            ['id' => 4, 'code' => 'tax-return', 'name' => 'Tax Return'],
            ['id' => 5, 'code' => 'withholding-agent', 'name' => 'Withholding Agent'],
            ['id' => 6, 'code' => 'petroleum-management', 'name' => 'Petroleum Management'],
            ['id' => 7, 'code' => 'return-verification', 'name' => 'Return Verification'],
            ['id' => 8, 'code' => 'tax-claim', 'name' => 'Tax Claim'],
            ['id' => 9, 'code' => 'auditing', 'name' => 'Auditing'],
            ['id' => 10, 'code' => 'investigation', 'name' => 'Investigation'],
            ['id' => 11, 'code' => 'dispute-management', 'name' => 'Dispute Management'],
            ['id' => 12, 'code' => 'tax-clearance-management', 'name' => 'Tax Clearance Management'],
            ['id' => 13, 'code' => 'debt-management', 'name' => 'Debt Management'],
            ['id' => 14, 'code' => 'managerial-report', 'name' => 'Manageria Report'],
            ['id' => 15, 'code' => 'land-lease', 'name' => 'Land Lease'],


        ];
        foreach ($data as $row) {
            SysModule::updateOrCreate($row);
        }
    }
}
