<?php

namespace Database\Seeders;

use App\Imports\ISIC1Import;
use Exception;
use Illuminate\Database\Seeder;

class ISIC1Seeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        try{
            $import = new ISIC1Import;
            $import->import(public_path('isics/ISIC_LEVEL_1.xlsx'));
        }catch(Exception $e){
            echo $e->getMessage();
        }
    }
}
