<?php

namespace Database\Seeders;

use App\Models\Region;
use App\Models\TaxRegion;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class TaxRegionsSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $departments = DB::table('tax_departments')->get();

        $regions = [
            [
                'department_id' => null,
                'code' => 'headquarter',
                'name' => 'Headquarter',
                'prefix' => '01',
                'location' => Region::UNGUJA,
            ],
            [
                'department_id' => $departments->where('code', 'DTD')->first()->id,
                'code' => 'mjini',
                'name' => 'Mjini',
                'prefix' => '02',
                'location' => Region::DTD,
            ],
            [
                'department_id' => $departments->where('code', 'DTD')->first()->id,
                'code' => 'magharib',
                'name' => 'magharib',
                'prefix' => '03',
                'location' => Region::DTD,
            ],
            [
                'department_id' => $departments->where('code', 'DTD')->first()->id,
                'code' => 'kaskazini-unguja',
                'name' => 'Kaskazini Unguja',
                'prefix' => '04',
                'location' => Region::DTD,
            ],
            [
                'department_id' => $departments->where('code', 'DTD')->first()->id,
                'code' => 'kusini-unguja',
                'name' => 'Kusini Unguja',
                'prefix' => '05',
                'location' => Region::DTD,
                
            ],
            [
                'department_id' => $departments->where('code', 'NTRD')->first()->id,
                'code' => 'NTRD',
                'name' => 'NTRD',
                'prefix' => '11',
                'location' => Region::NTRD,
            ],
            [
                'department_id' => $departments->where('code', 'PEMBA')->first()->id,
                'code' => 'kaskazini-pemba',
                'name' => 'Kaskazini Pemba',
                'prefix' => '06',
                'location' => Region::PEMBA,
            ],
            [
                'department_id' => $departments->where('code', 'PEMBA')->first()->id,
                'code' => 'kusini-pemba',
                'name' => 'Kusini Pemba',
                'prefix' => '07',
                'location' => Region::PEMBA,
            ],
            [
                'department_id' => $departments->where('code', 'LTD')->first()->id,
                'code' => 'company',
                'name' => 'company',
                'prefix' => '08',
                'location' => Region::LTD,
            ],
            [
                'department_id' => $departments->where('code', 'LTD')->first()->id,
                'code' => 'special-sector',
                'name' => 'special sector',
                'prefix' => '09',
                'location' => Region::LTD,
            ],
            [
                'department_id' => $departments->where('code', 'LTD')->first()->id,
                'code' => 'hotel',
                'name' => 'hotel',
                'prefix' => '10',
                'location' => Region::LTD,
            ],
        ];

        foreach ($regions as $region) {
            TaxRegion::updateOrCreate(
                [
                    'code' => $region['code']
                ],
                [
                    'department_id' => $region['department_id'],
                    'code' => $region['code'],
                    'name' => $region['name'],
                    'prefix' => $region['prefix'],
                    'location' => $region['location'],
                ]
            );
        }
    }
}
