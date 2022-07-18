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
            'reference_no' => '556677',
            'first_name' => 'John',
            'middle_name' => 'Tim',
            'last_name' => 'Doe',
            'email' => 'meshackf1@gmail.com',
            'mobile' => '0700000000',
            'alt_mobile' => '0754555555',
            'location' => 'Unguja',
            'physical_address' => 'P.O.Box 887, Unguja, Zanzibar.',
            'street' => 'Main Street',
            'is_citizen' => true,
            'is_first_login' => false,
            'id_type' => '1',
            'id_number' => '12312123123123123',
            'work_permit' => 'sample',
            'residence_permit' => 'sample',
            'country_id' => 1,
            'biometric_verified_at' => Carbon::now()->toDateTimeString(),
            'authorities_verified_at' => Carbon::now()->toDateTimeString(),
            'password' => Hash::make('password'),
        ]);

        $taxpayer = Taxpayer::create([
            'reference_no' => '556688',
            'first_name' => 'Jane',
            'middle_name' => 'Middle',
            'last_name' => 'Doe',
            'email' => 'v.meshack@live.co.uk',
            'mobile' => '0700000001',
            'alt_mobile' => '0754555555',
            'location' => 'Unguja',
            'physical_address' => 'P.O.Box 887, Unguja, Zanzibar.',
            'street' => 'Main Street',
            'is_citizen' => true,
            'is_first_login' => false,
            'id_type' => '1',
            'id_number' => '12312123123123123',
            'work_permit' => 'sample',
            'residence_permit' => 'sample',
            'country_id' => 1,
            'biometric_verified_at' => Carbon::now()->toDateTimeString(),
            'authorities_verified_at' => Carbon::now()->toDateTimeString(),
            'password' => Hash::make('password'),
        ]);

        TaxAgent::create([
            'tin_no' => 123123,
            'plot_no' => 123123,
            'block' => 'Block A',
            'town' => 'Unguja',
            'region' => 'Unguja',
            'reference_no' => 'ZRB909090',
            'status' => 'drafting',
            'taxpayer_id' => $taxpayer->id
        ]);
    }
}
