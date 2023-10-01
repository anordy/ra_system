<?php

namespace Database\Seeders;

use App\Models\Region;
use App\Models\TaxAgent;
use App\Models\Taxpayer;
use Carbon\Carbon;
use Faker\Factory;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;

class TaxpayersTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $users = [
            [
                'reference_no' => 'ZU2300006',
                'first_name' => 'Phillip',
                'middle_name' => '',
                'last_name' => 'Morro',
                'email' => 'phillip.morro@ubx.co.tz',
                'mobile' => '0763218007',
                'region_id' => 1,
                'physical_address' => '',
                'street_id' => null,
                'is_citizen' => true,
                'is_first_login' => false,
                'id_type' => '',
                'passport_no' => '',
                'permit_number' => '',
                'country_id' => 1,
                'biometric_verified_at' => now()->toDateTimeString(),
                'password' => Hash::make('password'),
                'pass_expired_on' => Carbon::now()->addYear()
            ], [
                'reference_no' => 'ZU2300008',
                'first_name' => 'Tabitha',
                'middle_name' => '',
                'last_name' => 'Mkude',
                'email' => 'tabitha.mkude@ubx.co.tz',
                'mobile' => '0748570624',
                'region_id' => 1,
                'physical_address' => '',
                'street_id' => null,
                'is_citizen' => true,
                'is_first_login' => false,
                'id_type' => '',
                'passport_no' => '',
                'permit_number' => '',
                'country_id' => 1,
                'biometric_verified_at' => now()->toDateTimeString(),
                'password' => Hash::make('password'),
                'pass_expired_on' => Carbon::now()->addYear()
            ], [
                'reference_no' => 'ZU2300010',
                'first_name' => 'Safia',
                'middle_name' => '',
                'last_name' => 'Mzee',
                'email' => 'safia.mzee@zanrevenue.org',
                'mobile' => '0777490855',
                'region_id' => 1,
                'physical_address' => '',
                'street_id' => null,
                'is_citizen' => true,
                'is_first_login' => false,
                'id_type' => '',
                'passport_no' => '',
                'permit_number' => '',
                'country_id' => 1,
                'biometric_verified_at' => now()->toDateTimeString(),
                'password' => Hash::make('password'),
                'pass_expired_on' => Carbon::now()->addYear()
            ]
        ];

        // for ($i = 0; $i < 100; $i++) {
        //     $faker = Factory::create();
        //     Taxpayer::updateOrCreate([
        //         'reference_no' => random_int(100000,99999999),
        //         'first_name' => $faker->firstName(),
        //         'middle_name' => '',
        //         'last_name' => $faker->lastName(),
        //         'email' => $faker->email(),
        //         'mobile' => random_int(100000,99999999),
        //         'region_id' => 1,
        //         'physical_address' => 'P.O.Box 887, Unguja, Zanzibar.',
        //         'street_id' => 1,
        //         'is_citizen' => true,
        //         'is_first_login' => false,
        //         'id_type' => '1',
        //         'passport_no' => random_int(10000000000000,99999999999999999),
        //         'permit_number' => random_int(100000000,999999999999),
        //         'country_id' => 1,
        //         'biometric_verified_at' => Carbon::now()->toDateTimeString(),
        //         'password' => Hash::make('password'),
        //         'pass_expired_on' => Carbon::now()->addYear()
        //     ]);
        // }

        foreach ($users as $user) {
            Taxpayer::updateOrCreate([
                'reference_no' => $user['reference_no'],
                'first_name' => $user['first_name'],
                'middle_name' => $user['middle_name'],
                'last_name' => $user['last_name'],
                'email' => $user['email'],
                'mobile' => $user['mobile'],
                'region_id' => 1,
                'physical_address' => 'P.O.Box 887, Unguja, Zanzibar.',
                'street_id' => 1,
                'is_citizen' => true,
                'is_first_login' => false,
                'id_type' => '1',
                'passport_no' => random_int(10000000000000,99999999999999999),
                'permit_number' => random_int(100000000,999999999999),
                'country_id' => 1,
                'biometric_verified_at' => Carbon::now()->toDateTimeString(),
                'password' => Hash::make('password'),
                'pass_expired_on' => Carbon::now()->addYear()
            ]);
        }


    }
}
