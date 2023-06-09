<?php

namespace App\Console\Commands;

use App\Models\Business;
use App\Models\District;
use App\Models\Region;
use App\Models\VfmsWard;
use App\Models\Ward;
use App\Traits\Vfms\VfmsLocationTrait;
use Carbon\Carbon;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Storage;

class MapVfmsWards extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'map:vfmswards';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Command description';

    /**
     * Create a new command instance.
     *
     * @return void
     */
    public function __construct()
    {
        parent::__construct();
    }

    /**
     * Execute the console command.
     *
     * @return int
     */
    public function handle()
    {
        Log::channel('vfms')->info('Mapping VFMS Wards started');
        $this->line('Mapping VFMS Wards started');
        $this->map();
        $this->line('Mapping VFMS Wards finished');
        Log::channel('vfms')->info('Mapping VFMS Wards finished');

    }

    use VfmsLocationTrait;

    private function map(){

        $jsonContents = public_path('files/vfms_wards.json');
        $jsonString = file_get_contents($jsonContents);
        $data = json_decode($jsonString);
        $missingWards = [];
        DB::beginTransaction();
        foreach ($data as $key => $item){
            $locality_name = strtolower($item->locality_name);
            $ward = Ward::select('id', 'name')->whereRaw("LOWER(name) LIKE LOWER(?)", ["%{$locality_name}%"])->first();
            if ($ward){
                $this->addVfmsWardToZidras($ward, $item);
            } else {
                //TODO waiting for API from VFMS to tell us on which region and district is ward belong to
                $response = $this->vfmsCheck($item->locality_id);
                if ($response['data']){
                    $data = json_decode($response['data'], true);
                    $region = $this->checkRegion($data);
                    if ($region){
                        $this->addOrCheckDistrict($region, $data);
                    } else {
                        $this->addRegion($data);
                    }
                } else {
                    Log::channel('vfms')->error('No response data after new ward entry data to vfms');
                    Log::channel('vfms')->info($response);
                }
            }
        }
        DB::commit();
        $recordsInserted = Ward::where('created_at', '>',Carbon::now()->subMinutes(1))->count();
        $this->line('Data inserted: '. $recordsInserted);

        if ($missingWards){
            $jsonMissingWards = json_encode($missingWards);
            $filePath = public_path('files/missing_vfms_wards.json');
            file_put_contents($filePath, '');
            file_put_contents($filePath, $jsonMissingWards);
            $this->line('Missing Wards: '.count($missingWards));
        }
    }

}
