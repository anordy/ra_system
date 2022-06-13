<?php

namespace App\Jobs;

use App\Models\CargoSelection;
use App\Models\TblBillOfLading;
use App\Models\TblBolCargo;
use App\Models\TblCargo;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldBeUnique;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

class ProcessDischargeListUpdate implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    private $voyage;
    private $data;
    private $time;

    /**
     * Create a new job instance.
     *
     * @return void
     */
    public function __construct($voyage, $data, $updateTime) {
        $this->voyage = $voyage;
        $this->data = $data;
        $this->time = $updateTime;
    }

    /**
     * Execute the job.
     *
     * @return void
     */
    public function handle() {
        Log::channel('shipco')->info("UPDATING DISCHARGE: STARTING...\n");
        DB::beginTransaction();
        try {
            if (count($this->data['bolList']) <= 0){
//                shipco wants to delete the list
//                check if there is any pedning bill that belong to the voyage
                $cargoSelect = CargoSelection::query()->select('id')->where('cargo_tbl_bill_of_ladings_tbl_voyages_id', $this->voyage['id'])->first();
                if ($cargoSelect){
                    Log::channel('shipco')->error("DELETE DISCHARGE: FAILED TO DELETE DISCHARGE LIST, POSSIBLE BILL PENDING");
                    return;
                }

//                delete cargo & bol that belongs to that voyage
                foreach (TblBolCargo::query()->where('tbl_bill_of_ladings_voyages_id', $this->voyage['id'])->get() as $bolCargo) {
                    $deletedCargo = TblCargo::query()->find($bolCargo->tbl_cargo_id);
                    if ($deletedCargo && !$deletedCargo->delete()){
                        Log::channel('shipco')->error("DELETE CARGO: FAILED TO DELETE CARGO: ".$bolCargo->tbl_bill_of_ladings_id);
                        DB::rollBack();
                        return;
                    }
                    $deletedBol = TblBillOfLading::query()->find($bolCargo->tbl_bill_of_ladings_id);
                    if ($deletedBol && !$deletedBol->delete()){
                        Log::channel('shipco')->error("DELETE BOL: FAILED TO DELETE BOL: ".$bolCargo->tbl_bill_of_ladings_id);
                        DB::rollBack();
                        return;
                    }
                    if (!TblBolCargo::query()->where(['tbl_bill_of_ladings_id' => $bolCargo->tbl_bill_of_ladings_id, 'tbl_cargo_id' => $bolCargo->tbl_cargo_id])->delete()){
                        Log::channel('shipco')->error("DELETE BOL-CARGO: FAILED TO DELETE BOL-CARGO");
                        DB::rollBack();
                        return;
                    }
                }
                $this->voyage->status = 'DELETED';
                if (!$this->voyage->save()){
                    DB::rollBack();
                    Log::channel('shipco')->error("UPDATING VOYAGE: FAILED TO UPDATE VOYAGE");
                    return;
                }
                DB::commit();
                Log::channel('shipco')->info("DELETING VOYAGE: SUCCESS");
                return;
            }
//        update voyage info
            $this->voyage->last_updated_at = $this->time;
            $this->voyage->status = 'MODIFIED';
            $this->voyage->arrival_date = $this->data['actualArrivalDate'];
            $this->voyage->departure_date = $this->data['expectedDepartureDate'];
            if (!$this->voyage->save()){
                DB::rollBack();
                Log::channel('shipco')->error("UPDATING DISCHARGE: FAILED TO MODIFY VOYAGE");
                return;
            }
            Log::channel('shipco')->info("UPDATING DISCHARGE: MODIFY VOYAGE SUCCESS");

            $sentCargo = [];
            $existingCargo = [];

//                get available cargo for existing bol
            $availableBolCargo = TblBolCargo::query()->select('tbl_cargo_id')
                ->where(['tbl_bill_of_ladings_voyages_id' => $this->voyage['id']])
                ->pluck('tbl_cargo_id')->all();

//        update bol info
            foreach ($this->data['bolList'] as $bol){
//                check if Bol exists
                $tblBol = TblBillOfLading::query()->where(['number' => $bol['blNumber'], 'tbl_voyages_id' => $this->voyage['id']])->first();
                if (!$tblBol){
//                    create new BOL
                    if (!$tblBol = TblBillOfLading::create([
                        'number' => $bol['blNumber'],
                        'consignee' => $bol['consignee'],
                        'notify' => $bol['notifyParty'],
                        'port_of_lading' => $bol['portOfLoading'],
                        'tbl_voyages_id' => $this->voyage['id']
                    ])){
                        DB::rollBack();
                        Log::channel('shipco')->error("UPDATING DISCHARGE: BOL DOES NOT EXIST");
                        return;
                    }
//                    insert cargo
                    foreach ($bol['cargoList'] as $cargo){
                        $cargoRules = [
                            'weight_kg' => $cargo['weight'],
                            'remarks' => $cargo['containerRemarks'],
                            'cargo_type' => $cargo['cargoType'],
                            'is_electric' => $cargo['isElectric'],
                            'content' => $cargo['containerContent'],
                            'cargo_land_status' => 'OVER',
                        ];
                        if ($cargo['cargoType'] == 'VEHICLE'){
                            $cargoRules['cbm'] = $cargo['cbm'];
                            $cargoNumber = $cargo['chassisNumber'];
                        }else{
                            $cargoRules['container_size'] = $cargo['containerSize'].'';
                            $cargoNumber = $cargo['containerNumber'];
                        }
                        $cargoRules['number'] = $cargoNumber;
//                        check if cargo number exist
                        $tblCargo = TblCargo::query()->where('number', $cargoNumber)->first();
                        if (!$tblCargo){
                            if (!$tblCargo = TblCargo::create($cargoRules)){
                                DB::rollBack();
                                Log::channel('shipco')->error("UPDATING DISCHARGE: FAILED TO CREATE OVER LANDED");
                                return;
                            }
                        }else{
                            if (!TblCargo::query()->where('number', $cargoNumber)->update($cargoRules)){
                                DB::rollBack();
                                Log::channel('shipco')->error("UPDATING DISCHARGE: FAILED TO UPDATE CARGO");
                            }
                        }
//                    create cargo bol relationship exist
                        if (!TblBolCargo::create([
                            'tbl_cargo_id' => $tblCargo['id'],
                            'tbl_bill_of_ladings_id' => $tblBol['id'],
                            'tbl_bill_of_ladings_voyages_id' => $this->voyage['id'],
                        ])){
                            DB::rollBack();
                            Log::channel('shipco')->error("UPDATING DISCHARGE: FAILED TO CREATE CARGO BOL R.SHIP");
                            return;
                        }
                    }
                }else{
//                    update existing BOL
                    $tblBol->notify = $bol['notifyParty'];
                    $tblBol->port_of_lading = $bol['portOfLoading'];
                    if (!$tblBol->save()){
                        DB::rollBack();
                        Log::channel('shipco')->error("UPDATING DISCHARGE: FAILED TO MODIFY BOL");
                        return;
                    }

                    $tblCargo = TblCargo::query()->select('number')->whereIn('id', $availableBolCargo)
                        ->pluck('number')->all();

//                    add existing cargo to array
                    foreach ($tblCargo as $exCargo){
                        $existingCargo[] = $exCargo;
                    }

                    foreach ($bol['cargoList'] as $cargo){
                        $cargoRules = [
                            'weight_kg' => $cargo['weight'],
                            'remarks' => $cargo['containerRemarks'],
                            'cargo_type' => $cargo['cargoType'],
                            'is_electric' => $cargo['isElectric'],
                            'content' => $cargo['containerContent'],
                        ];
                        if ($cargo['cargoType'] == 'VEHICLE'){
                            $cargoRules['cbm'] = $cargo['cbm'];
                            $cargoNumber = $cargo['chassisNumber'];
                        }else{
                            $cargoRules['container_size'] = $cargo['containerSize'].'';
                            $cargoNumber = $cargo['containerNumber'];
                        }
                        $sentCargo[] = $cargoNumber;
                        if (!in_array($cargoNumber, $tblCargo)){
//                            create over landed
                            $cargoRules['cargo_land_status'] = 'OVER';
                            $cargoRules['number'] = $cargoNumber;
                            if (!$newCargo = TblCargo::create($cargoRules)){
                                DB::rollBack();
                                Log::channel('shipco')->error("UPDATING DISCHARGE: FAILED TO CREATE OVER LANDED");
                                return;
                            }
//                            create cargo bol relationship
                            if (!TblBolCargo::create([
                                'tbl_cargo_id' => $newCargo['id'],
                                'tbl_bill_of_ladings_id' => $tblBol['id'],
                                'tbl_bill_of_ladings_voyages_id' => $this->voyage['id'],
                            ])){
                                DB::rollBack();
                                Log::channel('shipco')->error("UPDATING DISCHARGE: FAILED TO CREATE CARGO BOL R.SHIP");
                                return;
                            }
                            Log::channel('shipco')->info("NEW CARGO: ".$cargoNumber."\n");
                        }else{
//                            update cargo
                            if (!TblCargo::query()->where('number', $cargoNumber)->update($cargoRules)){
                                DB::rollBack();
                                Log::channel('shipco')->error("UPDATING DISCHARGE: FAILED TO UPDATE CARGO");
                            }
                            Log::channel('shipco')->info("EXISTING CARGO: ".$cargoNumber."\n");
                        }
                    }
                }
            }

            Log::channel('shipco')->info("AVAILABLE CARGO: \n".implode(', ', $existingCargo));
            Log::channel('shipco')->info("SENT CARGO: \n".implode(', ', $sentCargo));
//            create short landed if exists
            foreach ($existingCargo as $shortCargo){
                if (!in_array($shortCargo, $sentCargo)){
                    Log::channel('shipco')->info("CARGO REMOVED: ".$shortCargo."\n");
                    if (!TblCargo::query()->where('number', $shortCargo)->first()->update(['cargo_land_status' => 'SHORT'])){
                        DB::rollBack();
                        Log::channel('shipco')->error("UPDATING DISCHARGE: FAILED TO CREATE SHORT LANDED");
                        return;
                    }
                }else{
                    Log::channel('shipco')->info("CARGO FOUND: ".$shortCargo."\n");
                }
            }
            DB::commit();
            Log::channel('shipco')->info("UPDATING DISCHARGE: SUCCESSFUL");
        }catch (\Throwable $ex){
            DB::rollBack();
            Log::channel('shipco')->error("UPDATING DISCHARGE: \n".$ex);
        }
    }
}
