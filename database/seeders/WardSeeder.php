<?php

namespace Database\Seeders;

use App\Models\District;
use App\Models\Ward;
use Illuminate\Database\Seeder;

class WardSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {

        $KaskaziniAWards = ['Bandamaji', 'Chaani', 'Kubwa', 'Chaani', 'Masingini', 'Fukuchani', 'Gamba', 'Kandwi', 'Kibeni', 'Kidoti', 'Kigunda', 'Kijini', 'Kikobweni', 'Kinyasini', 'Kivinge', 'Matemwe', 'Mchena', 'Shauri', 'Mkokotoni', 'Mkwajuni', 'Moga', 'Mto', 'Pwani', 'Muwange', 'Nungwi', 'Pale', 'Pitanazako', 'Potoa', 'Pwani', 'Mchangani', 'Tazari', 'Tumbatu', 'Gomani', 'Tumbatu', 'Jongowe'];
        $kaskaziniA = District::where('name', 'Kaskazini A')->first();
        foreach ($KaskaziniAWards as $name) {
            Ward::updateOrCreate([
                'name' => $name,
                'district_id' => $kaskaziniA->id,
            ]);
        }

        $KaskaziniBWards = ['Done Mchagani','Donge Karange','Donge Kipange','Donge Mbiji','Donge Mnyimbi','Donge Mtambile','Donge Vijibweni','Fujoni','Kinduni', 'Kiomba Mvua', 'Kiombero', 'Kitope', 'Kiwengwa', 'Mahonda', 'Makoba', 'Manga Pwani', 'Mgambo', 'Misufini', 'Mkadini', 'Muwanda', 'Pangeni', 'Upenja', 'Zingwe Zingwe'];
        $kaskaziniB = District::where('name', 'Kaskazini B')->first();
        foreach ($KaskaziniBWards as $name) {
            Ward::updateOrCreate([
                'name' => $name,
                'district_id' => $kaskaziniB->id,
            ]);
        }

        $WeteWards = ['Bopwe', 'Fundo', 'Gando', 'Jadida', 'Kangagani', 'Kipangani', 'Kisiwani', 'Kizimbani', 'Kojani', 'Limbani', 'Mchanga', 'Mdogo', 'Mtambwe', 'Ole', 'Pandani', 'Selemu', 'Shengejuu', 'Utaani'];
        $Wete = District::where('name', 'Wete')->first();
        foreach ($WeteWards as $name) {
            Ward::updateOrCreate([
                'name' => $name,
                'district_id' => $Wete->id,
            ]);
        }


        $MicheweniWards = ['Kinowe', 'Kiuyu', 'Maziwa', "Ng'ombe", 'Konde', 'Mgogoni', 'Micheweni', 'Msuka', 'Shumba', 'Viamboni', 'Tumbe', 'Wingwi', 'Mapofu', 'Wingwi', 'Njuguni'];
        $Micheweni = District::where('name', 'Micheweni')->first();
        foreach ($MicheweniWards as $name) {
            Ward::updateOrCreate([
                'name' => $name,
                'district_id' => $Micheweni->id,
            ]);
        }
    }
}
