<?php

namespace Database\Seeders;

use App\Models\KYC;
use Illuminate\Database\Seeder;

class KYCSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        KYC::updateOrCreate([
            'reference_no' => '112233',
            'id_type' => 1,
            'first_name' => 'Dazy',
            'last_name' => 'Audax',
            'physical_address' => 'Mbweni',
            'street' => 'Mazizini',
            'email' => 'dazy@audax.com',
            'mobile' => '0175580888',
            'location' => 'Unguja',
            'is_citizen' => true,
            'country_id' => 1,
            'id_type' => 1

        ]);
    }
}
