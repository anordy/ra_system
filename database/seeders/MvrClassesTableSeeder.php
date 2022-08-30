<?php

namespace Database\Seeders;

use App\Models\MvrClass;
use App\Models\MvrFuelType;
use App\Models\MvrMake;
use App\Models\MvrModel;
use Illuminate\Database\Seeder;

class MvrClassesTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        MvrClass::query()->updateOrcreate(['code'=>'AA', 'name'=>'Pedal Cycle', 'category'=>'C']);
        MvrClass::query()->updateOrcreate(['code'=>'AB', 'name'=>'Pedal Cycle with power assist less than 200 watts', 'category'=>'C']);
        MvrClass::query()->updateOrcreate(['code'=>'BA', 'name'=>'Moped / Mate 50', 'category'=>'C']);
        MvrClass::query()->updateOrcreate(['code'=>'BB', 'name'=>'Moped with three wheels', 'category'=>'C']);
        MvrClass::query()->updateOrcreate(['code'=>'CA', 'name'=>'Motorcyle <= 125cc', 'category'=>'C']);
        MvrClass::query()->updateOrcreate(['code'=>'CB', 'name'=>'Motorcycle with side car', 'category'=>'C']);
        MvrClass::query()->updateOrcreate(['code'=>'CC', 'name'=>'Motor Tri-cycle', 'category'=>'C']);
        MvrClass::query()->updateOrcreate(['code'=>'DA', 'name'=>'Passenger Car less than 7 seats', 'category'=>'A']);
        MvrClass::query()->updateOrcreate(['code'=>'DB', 'name'=>'Light Passenger Vehicle < 12 seats', 'category'=>'A']);
        MvrClass::query()->updateOrcreate(['code'=>'DC', 'name'=>'Medium Passenger Vehicle (more than 12 seats < 1tn', 'category'=>'A']);
        MvrClass::query()->updateOrcreate(['code'=>'DD', 'name'=>'Heavy Passenger Vehicle (3.5 tons to 5 tons )', 'category'=>'A']);
        MvrClass::query()->updateOrcreate(['code'=>'EA', 'name'=>'Light Goods Vehicle (less than 3.5 tons)', 'category'=>'B']);
        MvrClass::query()->updateOrcreate(['code'=>'EB', 'name'=>'Medium Goods Vehicle (between 3.5 and 5 tons)', 'category'=>'B']);
        MvrClass::query()->updateOrcreate(['code'=>'EC', 'name'=>'Heavy Goods Vehicle (between 10 and 15 tons)', 'category'=>'B']);
        MvrClass::query()->updateOrcreate(['code'=>'FA', 'name'=>'Light Trailer (not more than than 1 tons)', 'category'=>'B']);
        MvrClass::query()->updateOrcreate(['code'=>'FB', 'name'=>'Medium Trailer  (between 3.5 and 5 tons)', 'category'=>'B']);
        MvrClass::query()->updateOrcreate(['code'=>'FC', 'name'=>'Heavy Trailer (over 10 tons)', 'category'=>'B']);
        MvrClass::query()->updateOrcreate(['code'=>'GA', 'name'=>'Light Tractor (under 5 tons - Agriculture)', 'category'=>'D']);
        MvrClass::query()->updateOrcreate(['code'=>'GB', 'name'=>'Heavy Tractor (over 3.5 tons not for Agriculture)', 'category'=>'D']);
        MvrClass::query()->updateOrcreate(['code'=>'HA', 'name'=>'Light Mobile Machine (under 5 tons - self propel)', 'category'=>'D']);
        MvrClass::query()->updateOrcreate(['code'=>'HB', 'name'=>'Heavy Mobile Machine (over 5 tons - self propel)', 'category'=>'D']);
        MvrClass::query()->updateOrcreate(['code'=>'EA1', 'name'=>'Light Good Vehicle <  1.0 tons', 'category'=>'B']);
        MvrClass::query()->updateOrcreate(['code'=>'EA2', 'name'=>'Light Good Vehicle > 1 Tone but <=2 tons', 'category'=>'B']);
        MvrClass::query()->updateOrcreate(['code'=>'EA3', 'name'=>'Light Good Vehicle > 2 tones but < 3.5 tons', 'category'=>'B']);
        MvrClass::query()->updateOrcreate(['code'=>'CA1', 'name'=>'Motocycle > 125cc', 'category'=>'C']);
        MvrClass::query()->updateOrcreate(['code'=>'DD1', 'name'=>'Heavy Passenger Vehicle (5 tons to 7 tons)', 'category'=>'A']);
        MvrClass::query()->updateOrcreate(['code'=>'DA1', 'name'=>'Passenger Car Greater than 7 seats', 'category'=>'A']);
        MvrClass::query()->updateOrcreate(['code'=>'DB1', 'name'=>'Light Passenger Vehicle > 12 seats', 'category'=>'A']);
        MvrClass::query()->updateOrcreate(['code'=>'DC1', 'name'=>'Medium Passenger Vehicle > 12 seats <= 2tn', 'category'=>'A']);
        MvrClass::query()->updateOrcreate(['code'=>'DC2', 'name'=>'Medium Passenger Vehicle > 12 seats >= 2tn < 3.5 t', 'category'=>'A']);
        MvrClass::query()->updateOrcreate(['code'=>'DD2', 'name'=>'Heavy Passenger Vehicle (7 tons and above)', 'category'=>'A']);
        MvrClass::query()->updateOrcreate(['code'=>'GB1', 'name'=>'Heavy Tractor used for Agriculture (Exempted)', 'category'=>'D']);
        MvrClass::query()->updateOrcreate(['code'=>'ED', 'name'=>'Light and medium sized motor vehicles and trailer', 'category'=>'B']);
        MvrClass::query()->updateOrcreate(['code'=>'EB1', 'name'=>'Medium Goods Vehicle (between 5 and 7 tons)', 'category'=>'B']);
        MvrClass::query()->updateOrcreate(['code'=>'EB2', 'name'=>'Medium Goods Vehicle (between 7 and 10 tons)', 'category'=>'B']);
        MvrClass::query()->updateOrcreate(['code'=>'EC1', 'name'=>'Heavy Goods Vehicle (over 15 tons)', 'category'=>'B']);
        MvrClass::query()->updateOrcreate(['code'=>'FA1', 'name'=>'Heavy Goods Vehicle (between 1 and 2 tons)', 'category'=>'B']);
        MvrClass::query()->updateOrcreate(['code'=>'FA2', 'name'=>'Heavy Goods Vehicle (between 2 and 3.5 tons)', 'category'=>'B']);
        MvrClass::query()->updateOrcreate(['code'=>'FB1', 'name'=>'Medium Trailer  (between 5 and 7 tons)', 'category'=>'B']);
        MvrClass::query()->updateOrcreate(['code'=>'FB2', 'name'=>'Medium Trailer  (between 7 and 10 tons)', 'category'=>'B']);
        MvrClass::query()->updateOrcreate(['code'=>'DAX', 'name'=>'Passenger Car < 7 seats 1501cc - 2500cc', 'category'=>'A']);
        MvrClass::query()->updateOrcreate(['code'=>'DAY', 'name'=>'Passenger Car < 7 seats 2501cc - 5000cc', 'category'=>'A']);
        MvrClass::query()->updateOrcreate(['code'=>'DAZ', 'name'=>'Passenger Car < 7 seats > 5000cc', 'category'=>'A']);
        MvrClass::query()->updateOrcreate(['code'=>'DBX', 'name'=>'Light Passenger Veh < 12 seats 1501cc - 2500cc', 'category'=>'A']);
        MvrClass::query()->updateOrcreate(['code'=>'DBY', 'name'=>'Light Passenger Veh < 12 seats 2501cc- 5000cc', 'category'=>'A']);
        MvrClass::query()->updateOrcreate(['code'=>'DBZ', 'name'=>'Light Passenger Veh < 12 seats > 5000cc', 'category'=>'A']);
        MvrClass::query()->updateOrcreate(['code'=>'DCW', 'name'=>'Medium Passenger Veh 1500cc - 3000cc', 'category'=>'A']);
        MvrClass::query()->updateOrcreate(['code'=>'DCX', 'name'=>'Medium Passenger Veh 2501cc - 3500cc', 'category'=>'A']);
        MvrClass::query()->updateOrcreate(['code'=>'DCY', 'name'=>'Medium Passenger Veh 3501cc - 5000cc', 'category'=>'A']);
        MvrClass::query()->updateOrcreate(['code'=>'DCZ', 'name'=>'Medium Passenger Veh > 5000cc', 'category'=>'A']);
        MvrClass::query()->updateOrcreate(['code'=>'DC3', 'name'=>'Medium Passenger Veh 501cc - 1500cc', 'category'=>'A']);
        MvrClass::query()->updateOrcreate(['code'=>'DC4', 'name'=>'Medium Passenger Veh 1501cc - 2500cc', 'category'=>'A']);
        MvrClass::query()->updateOrcreate(['code'=>'DC5', 'name'=>'Medium Passenger Veh 2501cc - 5000cc', 'category'=>'A']);
        MvrClass::query()->updateOrcreate(['code'=>'DC6', 'name'=>'Medium Passenger Veh > 5000cc', 'category'=>'A']);
        MvrClass::query()->updateOrcreate(['code'=>'DC7', 'name'=>'Medium Passenger Veh 501cc - 1500cc', 'category'=>'A']);
        MvrClass::query()->updateOrcreate(['code'=>'DC8', 'name'=>'Medium Passenger Veh 1501cc - 2500cc', 'category'=>'A']);
        MvrClass::query()->updateOrcreate(['code'=>'DC9', 'name'=>'Medium Passenger Veh 2501cc - 5000cc', 'category'=>'A']);
        MvrClass::query()->updateOrcreate(['code'=>'DC0', 'name'=>'Medium Passenger Veh > 5000cc', 'category'=>'A']);
    }
}
