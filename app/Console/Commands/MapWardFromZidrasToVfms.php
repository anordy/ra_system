<?php

namespace App\Console\Commands;

use App\Models\VfmsWard;
use App\Models\Ward;
use App\Traits\Vfms\VfmsLocationTrait;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

class MapWardFromCRDBToVfms extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'map:crdbToVfms';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Command description';

    protected $totalWardMapped = [];

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
        Log::channel('vfms')->info('Mapping CRDB Wards To VFMS Locality started');
        $this->line('Mapping CRDB Wards To VFMS Locality started');
        $this->map();
        $this->line('Mapping CRDB Wards To VFMS Locality finished');
        Log::channel('vfms')->info('Mapping CRDB Wards To VFMS Locality finished');
    }

    use VfmsLocationTrait;

    function map(){
        $wardIDS = Ward::whereDoesntHave('vfms_ward')->pluck('id')->toArray();
        foreach ($wardIDS as $id) {
            $ward = Ward::find($id);
            $this->getSendWardData($ward);
        }
        $this->line('Wards mapped: '. count($this->totalWardMapped). ' => ' .implode(', ', $this->totalWardMapped));
    }

    function getSendWardData($ward){
        $payload = $ward->vfmsLocalityData();
        $response = $this->addWardToVfms($payload);
        if ($response['data']){
            $data = json_decode($response['data'], true);
            DB::beginTransaction();
            try {
                if (array_key_exists('statusCode', $data)){
                    Log::channel('vfms')->info('Ward already exists on VFMS records, please kindly report to administrator.');
                    Log::channel('vfms')->info($data);
                    DB::rollBack();
                } else {
                    VfmsWard::create([
                        'ward_id' => $ward->id,
                        'locality_id' => $data['locality_id'],
                        'locality_name' => $ward->name,
                    ]);
                    DB::commit();
                    $this->totalWardMapped[] = $ward->name;
                }
            } catch (\Exception $e){
                Log::error($e);
                DB::rollBack();
            }
        } else {

            Log::channel('vfms')->info('No response data after new ward entry to VFMS, please kindly report to administrator.');
            Log::channel('vfms')->info((string)$response);

            $message = "This alert email concerning Mapping Vfms Locality data with CRDB wards. Inspect the logs as no response after new ward created on VFMS side.";
            $this->sendnotificationToAdmin($message);
        }
    }
}
