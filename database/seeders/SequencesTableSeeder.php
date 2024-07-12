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
        $sequences = [
            [
                'name' => 'taxpayerReferenceNo',
                'prefix' => 'TRN',
                'next_id' => 1,
            ],
            [
                'name' => 'vatRegistrationNo',
                'prefix' => 'VRN',
                'next_id' => 1,
            ],
            [
                'name' => 'consultantReferenceNo',
                'prefix' => 'CRN',
                'next_id' => 1,
            ],
            [
                'name' => Sequence::ASSESSMENT_NUMBER,
                'prefix' => 'ASN',
                'next_id' => 1,
                'next_sequence' => '01/000',
            ],
            // You can add more sequences here as needed
        ];

        foreach ($sequences as $sequence) {
            Sequence::firstOrCreate(
                ['name' => $sequence['name']],  // The attribute to check for existence
                $sequence  // All attributes to set if creating a new record
            );
        }
    }
}