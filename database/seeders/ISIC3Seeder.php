<?php

namespace Database\Seeders;

use App\Imports\ISIC3Import;
use Exception;
use Illuminate\Database\Seeder;

class ISIC3Seeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        try{
            $import = new ISIC3Import;
            $import->import(public_path('imports/ISIC_LEVEL_3.xlsx'));
        }catch(Exception $e){
            echo $e->getMessage();
        }
    }
}
