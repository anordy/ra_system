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
            ['id' => 2, 'code' => 'business-management' ,'name' => 'Business Management'],
            ['id' => 3, 'code' => 'tax-consultant', 'name' => 'Tax Consultant'],
            ['id' => 4, 'code' => 'upgrade-tax-types', 'name' => 'UpgradTax Types'],
            ['id' => 5, 'code' => 'tax-return', 'name' => 'Tax Return'],
            ['id' => 6, 'code' => 'withholding-agent', 'name' => 'Withholding Agent'],
            ['id' => 7, 'code' => 'petroleum-management', 'name' => 'Petroleum Management'],
            ['id' => 8, 'code' => 'return-verification', 'name' => 'Return Verification'],
            ['id' => 9, 'code' => 'tax-claim', 'name' => 'Tax Claim'],
            ['id' => 10, 'code' => 'auditing', 'name' => 'Auditing'],
            ['id' => 11, 'code' => 'investigation', 'name' => 'Investigation'],
            ['id' => 12, 'code' => 'dispute-management', 'name' => 'Dispute Management'],
            ['id' => 13, 'code' => 'tax-clearance-management', 'name' => 'Tax Clearance Management'],
            ['id' => 14, 'code' => 'debt-management', 'name' => 'Debt Management'],
            ['id' => 15, 'code' => 'land-lease', 'name' => 'Land Lease'],
            ['id' => 16, 'code' => 'managerial-report', 'name' => 'Manageria Report'],
            ['id' => 17, 'code' => 'manage-payment', 'name' => 'Manage Payment'],
            ['id' => 18, 'code' => 'setting', 'name' => 'Setting'],
            ['id' => 19, 'code' => 'system', 'name' => 'System'],


        ];
        foreach ($data as $row) {
            SysModule::updateOrCreate($row);
        }
    }
}
