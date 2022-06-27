<?php

namespace Database\Seeders;

use Carbon\Carbon;
use Illuminate\Database\Seeder;
use App\Models\WithholdingAgent;

class WithholdingAgentSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        //
        WithholdingAgent::create([
            'tin' => 434543960,
            'address' => 'PO BOX 345 Ilala, Dar es salaam',
            'wa_number' => 123456789,
            'institution_name' => 'Necta',
            'institution_place' => 'Amani',
            'email' => 'necta@go.tz',
            'mobile' => '0743900900',
            'position' => 'Director',
            'title' => 'Mr',
            'responsible_person_id' => 1,
            'officer_id' => 1,
            'region_id' => 1,
            'district_id' => 1,
            'ward_id' => 1,
            'date_of_commencing' => Carbon::now()->toDateTimeString(),
        ]);

        WithholdingAgent::create([
            'tin' => 545049506,
            'address' => 'PO BOX 139 Posta, Dar es salaam',
            'wa_number' => 2345678901,
            'institution_name' => 'UNHCR',
            'institution_place' => '23 Kibeni Road',
            'email' => 'unhcr@un.org',
            'mobile' => '0692700700',
            'position' => 'Chairman',
            'title' => 'Dr',
            'responsible_person_id' => 1,
            'officer_id' => 1,
            'region_id' => 1,
            'district_id' => 1,
            'ward_id' => 1,
            'date_of_commencing' => Carbon::now()->toDateTimeString(),
        ]);
    }
}
