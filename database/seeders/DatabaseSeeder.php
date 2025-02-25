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
        // $this->call(SysModuleSeeder::class);
        // $this->call(PermissionSeeder::class);
        // $this->call(RoleSeeder::class);
        // $this->call(UserSeeder::class);
        $this->call(BankChannelSeeder::class);
        $this->call(PrioritySeeder::class);
        $this->call(BankSystemSeeder::class);
        $this->call(CurrencySeeder::class);

    }
}
