<?php

namespace Database\Seeders;

use App\Models\TaxType;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Log;

class TaxTypePrefixSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        //
        $tax_types = TaxType::all();

        foreach ($tax_types as $key => $tax_type) {

            switch ($tax_type->id){
                case 1:
                    $prefix = 'A';
                    break;
                case 2:
                    $prefix = 'C';
                    break;
                case 3:
                    $prefix = 'E';
                    break;
                case 4:
                    $prefix = 'F';
                    break;
                case 5:
                    $prefix = 'N';
                    break;
                case 7:
                case 8:
                case 14:
                    $prefix = 'K';
                    break;
                case 9:
                    $prefix = 'J';
                    break;
                case 10:
                    $prefix = 'I';
                    break;
                case 11:
                    $prefix = 'G';
                    break;
                case 13:
                    $prefix = 'D';
                    break;
                case 15:
                    $prefix = 'O';
                    break;
                case 16:
                    $prefix = 'P';
                    break;
                case 19:
                    $prefix = 'M';
                    break;
                default:
                    $prefix = '';
                    break;
            }

            $tax_type->update([
                'prefix' => $prefix,
            ]);
        }
    }
}
