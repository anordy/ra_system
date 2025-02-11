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
            [
                'name' => Sequence::PLATE_ALPHABET,
                'prefix' => 'PA',
                'next_id' => 1,
                'next_sequence' => 'AA',
            ],
            [
                'name' => Sequence::PLATE_NUMBER,
                'prefix' => 'PN',
                'next_id' => 1,
                'next_sequence' => '101',
            ],
            [
                'name' => Sequence::SLS_PLATE_NUMBER,
                'prefix' => 'SLS',
                'next_id' => 1,
                'next_sequence' => '101',
            ],
            [
                'name' => Sequence::SMZ_PLATE_NUMBER,
                'prefix' => 'SMZ',
                'next_id' => 1,
                'next_sequence' => '101',
            ],
        ];

        foreach ($sequences as $sequence) {
            Sequence::firstOrCreate(
                ['name' => $sequence['name']],  // The attribute to check for existence
                $sequence  // All attributes to set if creating a new record
            );
        }
    }
}