<?php

namespace Database\Seeders;

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
            'first_name' => 'John',
            'middle_name' => 'Tim',
            'last_name' => 'Doe',
            'email' => 'john@doe.com',
            'mobile' => '0700000000',
            'region_id' => 1,
            'physical_address' => 'P.O.Box 887, Unguja, Zanzibar.',
            'street' => 'Main Street',
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

        $taxpayer = Taxpayer::create([
            'reference_no' => 'ZRB556688',
            'first_name' => 'Jane',
            'middle_name' => 'Middle',
            'last_name' => 'Doe',
            'email' => 'jane@doe.com',
            'mobile' => '0700000001',
            'region_id' => 1,
            'physical_address' => 'P.O.Box 887, Unguja, Zanzibar.',
            'street' => 'Main Street',
            'is_citizen' => true,
            'is_first_login' => false,
            'id_type' => '1',
            'passport_no' => '12312123123123123',
            'permit_number' => '32798329898989',
            'country_id' => 1,
            'biometric_verified_at' => Carbon::now()->toDateTimeString(),
            'password' => Hash::make('password'),
            'pass_expired_on' => Carbon::now()->addYear()
        ]);

        $taxpayer = Taxpayer::create([
            'reference_no' => 'ZRB556699',
            'first_name' => 'Meshack',
            'middle_name' => '',
            'tin' => '8998889998',
            'last_name' => 'Victor',
            'email' => 'meshackf1@gmail.com',
            'mobile' => '0753550590',
            'alt_mobile' => '0754555555',
            'region_id' => 1,
            'physical_address' => 'P.O.Box 887, Unguja, Zanzibar.',
            'street' => 'Main Street',
            'is_citizen' => true,
            'is_first_login' => false,
            'id_type' => '1',
            'passport_no' => '12312123123123123',
            'permit_number' => '329327798328989',
            'country_id' => 1,
            'biometric_verified_at' => Carbon::now()->toDateTimeString(),
            'password' => Hash::make('password'),
            'pass_expired_on' => Carbon::now()->addYear()
        ]);
    }
}
