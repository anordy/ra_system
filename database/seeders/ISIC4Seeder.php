<?php

namespace Database\Seeders;

use App\Imports\ISIC4Import;
use Exception;
use Illuminate\Database\Seeder;

class ISIC4Seeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        try{
            $import = new ISIC4Import;
            $import->import(public_path('isics/ISIC_LEVEL_4.xlsx'));
        }catch(Exception $e){
            echo $e->getMessage();
        }
    }
}
