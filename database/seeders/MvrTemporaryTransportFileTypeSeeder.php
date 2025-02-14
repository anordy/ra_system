<?php

namespace Database\Seeders;

use App\Models\MvrTemporaryTransportFileType;
use Illuminate\Database\Seeder;

class MvrTemporaryTransportFileTypeSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $types = [
            [
                'name' => 'Police Evidence',
                'code' => 'police-evidence',
                'description' => 'Police Evidence',
            ],
            [
                'name' => 'Release Letter',
                'code' => 'release-letter',
                'description' => 'Release Letter',
            ]
        ];

        foreach ($types as $type) {
            $type['file_type'] = 'pdf';
            $type['is_required'] = true;
            $type['is_approved'] = true;
            $type['is_updated'] = false;
            MvrTemporaryTransportFileType::updateOrCreate(['code' => $type['code']],$type);
        }
    }
}
