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

        $KaskaziniAWards =  ['Chaani Masingini','Mchenza Shauri','Chaani Kubwa','Bandamaji','Kikobweni','Kinyasini','Kandwi','Pwani Mchangani','Kigongoni','Potoa','Kijini Matemwe','Mbuyutende','Kigomani','Juga Kuu','Muwange','Pitanazako','Kivunge','Kibeni','Mkwajuni','Kidombo','Chutama','Moga','Matemwe Kaskazini','Matemwe Kusini','Gamba','Mtakuja','Gomani','Uvivini','Jongowe','Mto wa Pwani','Mkokotoni','Pale','Muwanda','Kipange','Mchangani','Bandakuu','Kiungani','Kilindi','Kigunda','Tazari','Kilimani Tazari','Kidoti','Bwereu','Fukuchani'];
        $kaskaziniADistrictID = District::where('name', 'Kaskazini A')->value('id');
        foreach ($KaskaziniAWards as $name) {
            Ward::updateOrCreate([
                'name' => $name,
                'district_id' => $kaskaziniADistrictID,
                'is_approved' => 1
            ]);
        }

        $KaskaziniBWards = ['Donge Mtambile','Njia ya Mtoni','Donge Vijibweni','Majenzi','Donge Karange','Donge Pwani','Donge Mbiji','Mnyimbi','Mkataleni','Matetema','Mahonda','Kinduni','Kwagube','Kitope','Mbaleni','Kilombero','Mgambo','Kisongoni','Pangeni','Upenja','Kiwengwa','Kiongwe Kidogo','Mafufuni','Makoba','Misufini','Kidanzini','Mangapwani','Fujoni','Zingwezingwe','Kiombamvua','Mkadini'];
        $kaskaziniBDIstrictID = District::where('name', 'Kaskazini B')->value('id');

        foreach ($KaskaziniBWards as $name) {
            Ward::updateOrCreate([
                'name' => $name,
                'district_id' => $kaskaziniBDIstrictID,
                'is_approved' => 1
            ]);
        }


        $micheweniWards = ['Makangale','Tondooni','Msuka Magharibi','Msuka Mashariki','Kipange','Konde','Kifundi','Shumba Mjini','Majenzi','Micheweni','Chamboni','Shanake','Kiuyu Mbuyuni',"Maziwa Ng'ombe",'Tumbe Magharibi','Tumbe Mashariki','Mihogoni','Kinowe','Chimba','Shumba Viamboni','Sizini','Mjini Wingwi','Wingwi Mapofu','Wingwi Njuguni','Mtemani'];
        $micheweniID = District::where('name', 'Micheweni')->value('id');

        foreach ($micheweniWards as $name) {
            Ward::updateOrCreate([
                'name' => $name,
                'district_id' => $micheweniID,
                'is_approved' => 1
            ]);
        }

        $weteWards = ['Gando','Junguni','Ukunjwi','Fundo','Mgogoni','Kinyasini','Finya','Kizimbani','Kambini','Kiuyu Minungwini','Kiuyu Kigongoni','Kangagani','Chwale','Kinyikani','Mchanga Mdogo','Mpambani','Kojani','Mtambwe Kaskazini','Mtambwe Kusini','Kisiwani','Limbani','Piki','Pandani','Mzambarau Takao','Maziwani','Mlindo','Mjananza','Kiungoni','Pembeni','Shengejuu','Selem','Kipangani','Jadida','Mtemani','Bopwe','Utaani'];
        $weteID = District::where('name', 'Wete')->value('id');

        foreach ($weteWards as $name) {
            Ward::updateOrCreate([
                'name' => $name,
                'district_id' => $weteID,
                'is_approved' => 1
            ]);
        }


        $katiWards = ['Ndijani Muembe Punda','Ndijani Mseweni','Cheju','Cheju Zuwiyani', 'Cheju Hanyegwa Mchana', 'Charawe','Ukongoroni','Pete','Pongwe','Uroa','Marumbi','Jendele','Chwaka','Kidimni','Koani','Machui','Miwani','Ghana','Kiboje Mwembeshauri','Tunduni','Mitakawani','Uzini','Kiboje Mkwajuni','Mgeni Haji','Mchangani Shamba','Kijibwe Mtu','Bambi','Mpapa','Pagali','Umbuji','Ubago','Dunga Bweni','Dunga Kiembeni','Binguni','Jumbi','Tunguu','Bungi','Kikungwi','Unguja Ukuu Kaebona','Tindini','Unguja Ukuu Kaepwani','Uzi',"Ng'ambwa"];
        $katiID = District::where('name', 'Kati')->value('id');

        foreach ($katiWards as $name) {
            Ward::updateOrCreate([
                'name' => $name,
                'district_id' => $katiID,
                'is_approved' => 1
            ]);
        }

        $kusiniWards = ['Michamvi','Dongwe','Bwejuu','Paje','Kitogani','Muungoni','Jambiani Kibigija','Jambiani Kikadini',"Muyuni A","Muyuni B","Muyuni C",'Kizimkazi Dimbani','Kibuteni','Kizimkazi Mkunguni','Mtende','Kajengwa','Nganani','Kijini','Kiongoni','Mzuri','Tasani'];
        $kusiniID = District::where('name', 'Kusini')->value('id');

        foreach ($kusiniWards as $name) {
            Ward::updateOrCreate([
                'name' => $name,
                'district_id' => $kusiniID,
                'is_approved' => 1
            ]);
        }


        $chakechakeWards = ["Madungu","Chanjaani","Shungi","Tibirinzi","Kichungwani","Chachani","Msingini","Kilindi","Mgelema","Chonga","Matale","Mfikiwa","Pujini","Ng'ambwa","Uwandani","Vitongoji","Mchanga Mrima","Mjini Ole","Ole","Mgogoni","Mvumoni","Kibokoni","Gombani","Mkoroshoni","Wara","Wawi","Ziwani","Kwale","Mbuzini","Ndagoni","Wesha","Michungwani"];
        $chakechakeID = District::where('name', 'Chakechake')->value('id');

        foreach ($chakechakeWards as $name) {
            Ward::updateOrCreate([
                'name' => $name,
                'district_id' => $chakechakeID,
                'is_approved' => 1
            ]);
        }


        $mkoaniWards = ["Dodo","Chambani","Ukutini","Wambaa","Chumbageni","Mgagadu","Ngwachani","Kendwa","Mtangani","Kiwani","Mchakwe","Shamiani","Mwambe","Jombwe","Makombeni","Ng'ombeni","Uweleni","Mbuguani","Changaweni","Makoongwe","Mbuyuni","Shidi","Michenzani","Stahabu","Mkanyageni","Chokocho","Mizingani","Mtambile","Mjimbini","Minazini","Kisiwapanza","Kangani","Kuukuu","Kengeja","Chole","Mkungu"];
        $mkoaniID = District::where('name', 'Mkoani')->value('id');

        foreach ($mkoaniWards as $name) {
            Ward::updateOrCreate([
                'name' => $name,
                'district_id' => $mkoaniID,
                'is_approved' => 1
            ]);
        }


        $mjiniWards = ["Kilimahewa Bondeni","Kilimahewa Juu","Amani","Kwa Wazee","Maruhubi","Mwembemakumbi","Masumbani","Banko","Karakana","Chumbuni","Kwaalinatu","Matarumbeta","Kidongo Chekundu","Jang'ombe","Urusi","Mnazimmoja","Kisiwandui","Kikwajuni Bondeni","Kisima Majongoo","Kikwajuni Juu","Mwembeladu","Rahaleo","Mwembeshauri","Miembeni","Muembemadema","Mikunguni","Kwaalimsha","Kwahani","Muungano","Sebleni","Saateni","Shaurimoyo","Mapinduzi","Mkele","Mboriborini","Kwamtipura","Sogea","Nyerere","Kwamtumwajeni","Meya","Magomeni","Shangani","Kiponda","Malindi","Mchangani Mjini","Mkunazini","Vikokotoni","Muembetanga","Mlandege","Gulioni","Mitiulaya","Makadara","Kilimani","Migombani","Kwa binti amrani","Mpendae"];
        $mjiniID = District::where('name', 'Mjini')->value('id');

        foreach ($mjiniWards as $name) {
            Ward::updateOrCreate([
                'name' => $name,
                'district_id' => $mjiniID,
                'is_approved' => 1
            ]);
        }


        $magharibiAWards = ["Sharifu Msa","Mtoni","Mwanyanya","Kwa Goa","Kibweni","Bububu","Kijichi","Mbuzini","Chemchem","Dole","Kizimbani","Mfenesini","Mwakaje","Bumbwisudi","Kama","Chuini","Kihinani","Kikaangoni","Mto Pepo","Munduli","Uholanzi","Welezo ","Hawaii","Michikichini","Mtofaani","Mtoni Kidatu","Mtoni Chemchem","Masingini","Kianga","Muembemchomeke","Mwera"];
        $magharibiAID = District::where('name', 'Magharibi A')->value('id');

        foreach ($magharibiAWards as $name) {
            Ward::updateOrCreate([
                'name' => $name,
                'district_id' => $magharibiAID,
                'is_approved' => 1
            ]);
        }
        

        $magharibiBWards = ["Tomondo","Kisauni","Maungani","Kombeni","Nyamanzi","Dimani","Bweleo","Fumba","Mambosasa","Chunga","Fuoni Migombani","Fuoni Kipungani","Uwandani","Kibondeni","Kiembesamaki","Mbweni","Kwa Mchina","Mombasa","Michungwani","Chukwani","Kiembe Samaki","Shakani","Magogoni","Jitimai","Sokoni","Mikarafuuni","Mwanakwerekwe","Melinne","Taveta","Muembe Majogoo","Pangawe","Kinuni","Mnarani","Uzi","Kijitoupele",];
        $magharibiBID = District::where('name', 'Magharibi B')->value('id');

        foreach ($magharibiBWards as $name) {
            Ward::updateOrCreate([
                'name' => $name,
                'district_id' => $magharibiBID,
                'is_approved' => 1
            ]);
        }
    }
}
