<?php

namespace Database\Seeders;

use App\Models\Ntr\NtrBusinessAttachmentType;
use Illuminate\Database\Seeder;

class NtrBusinessAttachmentTypeSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        NtrBusinessAttachmentType::create([
            'name' => 'Business Certificate',
            'is_for_entity' => false
        ]);

        NtrBusinessAttachmentType::create([
            'name' => 'Certificate of Incorporation',
            'is_for_entity' => true
        ]);
    }
}
