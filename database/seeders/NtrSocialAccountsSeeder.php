<?php

namespace Database\Seeders;

use App\Models\Ntr\NtrSocialAccount;
use Illuminate\Database\Seeder;

class NtrSocialAccountsSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $socialAccounts = ['Facebook', 'Instagram', 'Twitter'];

        foreach ($socialAccounts as $account) {
            NtrSocialAccount::updateOrCreate(
                [
                    'name' => $account
                ],
                [
                    'name' => $account, 'icon' => strtolower($account)
                ]
            );
        }
    }
}
