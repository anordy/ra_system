<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;

class NtrSeeders extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $this->call(NtrPaymentGatewaySeeder::class);
        $this->call(NtrBusinessAttachmentTypeSeeder::class);
        $this->call(NtrBusinessCategorySeeder::class);
        $this->call(NtrSocialAccountsSeeder::class);
        $this->call(NtrNatureOfBusinessSeeder::class);
        $this->call(NtrVatReturnConfigSeeder::class);
    }
}
