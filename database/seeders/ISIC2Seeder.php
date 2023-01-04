<?php

namespace Database\Seeders;

use App\Imports\ISIC2Import;
use Exception;
use Illuminate\Database\Seeder;

class ISIC2Seeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        try{
            $import = new ISIC2Import;
            $import->import(public_path('imports/ISIC_LEVEL_2.xlsx'));
        }catch(Exception $e){
            echo $e->getMessage();
        }
    }
}
