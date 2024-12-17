<?php

namespace Database\Seeders\Tra;

use App\Models\Tra\TraVehicleBodyType;
use Illuminate\Database\Seeder;

class VehicleBodyTypeSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $vehicleTypes = [
            '10' => 'Coupe (Open Top)',
            '11' => 'Station Wagon',
            '12' => 'Jeep',
            '13' => 'Combi / Micro Bus',
            '14' => 'Mini Bus',
            '15' => 'Bus',
            '16' => 'Pick-Up',
            '17' => 'Panel Wagon',
            '18' => 'Tourer',
            '19' => 'Van Body',
            '20' => 'Flat Deck / Platform Deck',
            '21' => 'Dropside',
            '22' => 'Tipper',
            '23' => 'Mixer',
            '24' => 'Tanker',
            '25' => 'Compactor Body',
            '26' => 'Equipment Platform / Low Bed',
            '27' => 'Caravan',
            '28' => 'Truck',
            '29' => 'Breakdown',
            '30' => 'Fire Engine',
            '31' => 'Ambulance',
            '32' => 'Rescue Vehicle',
            '33' => 'Logger Body',
            '34' => 'Sheet Glass Body',
            '35' => 'Tractor',
            '36' => 'Chassis-Cab',
            '37' => 'Chassis',
            '38' => 'Skeletal',
            '39' => 'Adapter Dolly',
            '40' => 'Converter Dolly',
            '41' => 'Hearse',
            '42' => 'Grader',
            '43' => 'Compactor',
            '44' => 'Roller',
            '45' => 'Loader',
            '46' => 'Crane',
            '47' => 'Tarmac Spreader',
            '48' => 'Digger',
            '49' => 'Backacter',
            '50' => 'Drill / Borer',
            '51' => 'Generator',
            '52' => 'Compressor',
            '53' => 'Sweeper',
            '54' => 'Pipelaying',
            '55' => 'Harvester',
            '56' => 'Baler',
            '57' => 'Planter',
            '58' => 'Hammer',
            '59' => 'Hearse / Ambulance',
            '60' => 'Roadmaking',
            '61' => 'Earthmoving',
            '62' => 'Excavation',
            '63' => 'Construction',
            '64' => 'Mass/Diesel Cart Farming',
            '65' => 'Utility Vehicle',
            '66' => 'Agriculture Machine',
            '67' => 'Mobile Equipment',
            '68' => 'Vehicle Carrier',
            '69' => 'Mesh Side Body',
            '70' => 'Trailer',
            '71' => 'Hardtop',
            '72' => 'Fork Lift',
            '73' => 'Dumper',
            '74' => 'Box Body',
            '75' => 'Limonsine',
            '76' => 'Asphalt Finisher',
            '77' => 'Bull Dozers',
            '78' => 'Bitumen',
            '79' => 'Cargo',
            '80' => 'Wheel Loader',
            '81' => 'Flat Bed',
            '82' => 'Convertible',
            '83' => 'Horse',
            '84' => 'Excavator',
            '85' => 'Stabilizer',
            '86' => 'Paver',
            '87' => 'Sprayer',
            '88' => 'Lion Loader',
            '89' => 'Soft Top',
            '90' => 'Motor Quadrucycle (Side By Side)',
            '91' => 'Prime Mover',
            '92' => 'Sprinkle',
            '93' => 'Suv',
            '00' => 'Not Applicable',
            '01' => 'Motorcycle (No Sidecar)',
            '02' => 'Motorcycle (With Sidecar)',
            '03' => 'Scooter',
            '04' => 'Motor Tricycle',
            '05' => 'Motor Quadrucycle',
            '06' => 'Beach Buggy',
            '07' => 'Saloon (Closed Top)',
            '08' => 'Saloon (Open Top)',
            '09' => 'Coupe (Closed Top)',
        ];

        foreach ($vehicleTypes as $code =>$type) {
            TraVehicleBodyType::updateOrCreate([
                'code' => $code
            ], [
                'code' => $code,
                'name' => $type
            ]);
        }

    }
}
