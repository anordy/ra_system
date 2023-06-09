<?php

namespace Database\Seeders;

use App\Models\Region;
use App\Models\TaxAgent;
use App\Models\Taxpayer;
use Carbon\Carbon;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class TaxpayersTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        Taxpayer::updateOrCreate([
            'reference_no' => 'ZRB556677',
            'first_name' => 'Imran',
            'middle_name' => 'Ali',
            'last_name' => 'Hassan',
            'email' => 'john@doe.com',
            'mobile' => '0700000000',
            'region_id' => 1,
            'physical_address' => 'P.O.Box 887, Unguja, Zanzibar.',
            'street_id' => 1,
            'is_citizen' => true,
            'is_first_login' => false,
            'id_type' => '1',
            'passport_no' => '12312123123123123',
            'permit_number' => '389439743943989',
            'country_id' => 1,
            'biometric_verified_at' => Carbon::now()->toDateTimeString(),
            'password' => Hash::make('password'),
            'pass_expired_on' => Carbon::now()->addYear()
        ]);
    }
}
