<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     *
     * @return void
     */
    public function run()
    {
        $this->call(IDTypesTableSeeder::class);
        $this->call(SysModuleSeeder::class);
        $this->call(PermissionSeeder::class);
        $this->call(RoleSeeder::class);
        $this->call(UserSeeder::class);
        $this->call(BusinessActivitiesSeeder::class);
        $this->call(CountriesTableSeeder::class);
        $this->call(RegionSeeder::class);
        $this->call(DistrictSeeder::class);
        $this->call(WardSeeder::class);
        $this->call(CurrenciesTableSeeder::class);
        $this->call(IDTypesTableSeeder::class);
        $this->call(BanksTableSeeder::class);
        $this->call(SysModuleSeeder::class);
        $this->call(TaxTypesSeeder::class);
        $this->call(TaxpayersTableSeeder::class);
        $this->call(BusinessCategoriesSeeder::class);
        $this->call(PoliticalDistributionSeeder::class);
        $this->call(WithholdingAgentSeeder::class);
    }
}
