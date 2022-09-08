<?php

namespace Database\Seeders;

use App\Models\Sequence;
use Illuminate\Database\Seeder;

class SequencesTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        Sequence::create([
            'name' => 'taxpayerReferenceNo',
            'prefix' => 'TRN',
            'next_id' => 1,
        ]);
        Sequence::create([
            'name' => 'vatRegistrationNo',
            'prefix' => 'VRN',
            'next_id' => 1,
        ]);
        Sequence::create([
            'name' => 'consultantReferenceNo',
            'prefix' => 'CRN',
            'next_id' => 1,
        ]);

        // Maybe add more for land lease, withholding agent, etc etc 🤷‍
    }
}
