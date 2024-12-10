<?php

namespace Database\Seeders;

use App\Models\Ntr\NtrIdType;
use Illuminate\Database\Seeder;

class NtrIdTypeSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $ids = [
            [
                'name' => 'Driving License',
            ],
            [
                'name' => 'National/Government Identification Number',
            ],
        ];

        foreach ($ids as $id) {
            NtrIdType::updateOrCreate(
                [
                    'name' => $id['name']
                ],
                [
                    'name' => $id['name']
                ]);
        }
    }
}
