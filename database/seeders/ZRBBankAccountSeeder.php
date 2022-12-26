<?php

namespace Database\Seeders;

use App\Models\ZrbBankAccount;
use Illuminate\Database\Seeder;

class ZRBBankAccountSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        //
        $bankAccounts = [
            ['bank_id' => 1, 'account_name' => 'ZANZIBAR REVENUE BOARD', 'account_number' => '0404003001', 'branch_name' => 'FORODHANI', 'currency_id' => '1', 'is_approved' => 1],
            ['bank_id' => 1, 'account_name' => 'ZANZIBAR REVENUE BOARD', 'account_number' => '0400714000', 'branch_name' => 'FORODHANI', 'currency_id' => '2', 'is_approved' => 1],
            ['bank_id' => 9, 'account_name' => 'ZANZIBAR REVENUE BOARD', 'account_number' => '9928130504', 'branch_name' => 'ZANZIBAR', 'currency_id' => '1', 'is_approved' => 1],
            ['bank_id' => 2, 'account_name' => 'ZANZIBAR REVENUE BOARD', 'account_number' => '0150661263800', 'branch_name' => 'ZANZIBAR', 'currency_id' => '1', 'is_approved' => 1],
            ['bank_id' => 2, 'account_name' => 'ZANZIBAR REVENUE BOARD', 'account_number' => '0250661263800', 'branch_name' => 'ZANZIBAR', 'currency_id' => '2', 'is_approved' => 1],
            ['bank_id' => 3, 'account_name' => 'ZANZIBAR REVENUE BOARD', 'account_number' => '22910053581', 'branch_name' => 'ZANZIBAR', 'currency_id' => '1', 'is_approved' => 1],
            ['bank_id' => 3, 'account_name' => 'ZANZIBAR REVENUE BOARD', 'account_number' => '22910053582', 'branch_name' => 'ZANZIBAR', 'currency_id' => '2', 'is_approved' => 1],
        ];

        foreach ($bankAccounts as $bankAccount) {
            // dd($bankAccount);
            ZrbBankAccount::updateOrCreate($bankAccount);
        }
    }
}
