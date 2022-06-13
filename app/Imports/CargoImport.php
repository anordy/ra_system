<?php

namespace App\Imports;

use Illuminate\Support\Collection;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Validator;
use Maatwebsite\Excel\Concerns\Importable;
use Maatwebsite\Excel\Concerns\SkipsEmptyRows;
use Maatwebsite\Excel\Concerns\ToCollection;
use Maatwebsite\Excel\Concerns\WithHeadingRow;
use Maatwebsite\Excel\Concerns\WithValidation;
use function GuzzleHttp\json_decode;
use function GuzzleHttp\json_encode;

class CargoImport implements ToCollection, WithHeadingRow, WithValidation, SkipsEmptyRows
{
    use Importable;
    private $errors = []; // array to accumulate errors

    protected $shipLineCode;
    protected $vesselName;
    protected $voyageNumber;
    protected $estimatedADate;
    protected $departureDate;

    public function __construct($_shipLineCode, $_vesselName, $_voyageNumber, $_estimatedADate, $_departureDate){
        $this->shipLineCode = $_shipLineCode;
        $this->vesselName = $_vesselName;
        $this->voyageNumber = $_voyageNumber;
        $this->estimatedADate = $_estimatedADate;
        $this->departureDate = $_departureDate;
    }

    /**
     * @param array $row
     *
     * @return \Illuminate\Database\Eloquent\Model|null
     */
    public function collection(collection $rows){
        $rows = $rows->toArray();
        foreach ($rows as $key=>$row){
            Log::info("IMPORTING_XLS: ".$row['b_l_number']);
            $validator = Validator::make($row, $this->rules(), $this->validationMessages());
            if ($validator->fails()){
                foreach ($validator->errors()->messages() as $messages) {
                    foreach ($messages as $error) {
                        // accumulating errors:
                        $this->errors[] = $error;
                    }
                }
            }else{
                $generalCargoTypeId = null;
                $containerTypeId = null;

                $cargoTypeId = DB::table('cargo_types')->select('id', 'code')
                    ->where('name', $row['cargo_type'])->first();
                $containerType = DB::table('container_types')->select('id')
                    ->where('type', $row['container_type'])->first();
                $generalCargoType = DB::table('general_cargo_types')->select('id')
                    ->where('type', $row['general_cargo_type'])->first();

                if ($cargoTypeId){
                    if ($cargoTypeId->code == 'CN001'){
//                        cargo is container
                        if (!$containerType){
                            $this->errors[] = 'Container type does not exist';
                            return null;
                        }
                        $containerTypeId = $containerType->id;
                    }else{
//                        cargo is not container
                        if (!$generalCargoType){
                            $this->errors[] = 'General cargo type does not exist';
                            return null;
                        }
                        $generalCargoTypeId = $generalCargoType->id;
                    }

                    $params = array(
                        $this->shipLineCode,
                        $this->vesselName,
                        $this->voyageNumber,
                        $this->estimatedADate,
                        $this->departureDate,
                        1,
                        $row['b_l_number'],
                        $row['consignee'],
                        $row['shipper'],
                        $row['notify'],
                        $row['pol'],
                        $row['cargo_no'],
                        $row['seal_no'],
                        $row['weight'],
                        $row['remarks'],
                        $cargoTypeId->id,
                        $containerTypeId,
                        $generalCargoTypeId
                    );

                    Log::info("MANIFEST_REQ: ".json_encode($params));
                    $manifest = DB::select('CALL sp_insert_manifest(?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?)',$params);

                    Log::info("MANIFEST_RES: ".json_encode($manifest));
					if ($manifest[0]->status_code != 300){
                        $this->errors[] = $manifest[0]->message;
                    }
                }else{
                    $this->errors[] = 'Cargo type does not exist';
                }
            }
        }
    }

    // this function returns all validation errors after import:
    public function getErrors(){
        return $this->errors;
    }

    public function validationMessages(){
        return [
            'cargo_no.required' => 'Cargo number is required',
            'Cargo_Type.required' => 'Cargo type is required',
            'Seal_No.required' => 'Seal number is required',
            'B_L_Number.required' => 'Bill of lading is required',
            'Consignee.required' => 'Consignee is required',
            'Shipper.required' => 'Shipper is required',
            'Notify.required' => 'Notify is required',
            'Container_Type.required' => 'Container type is required',
            'Weight.required' => 'Weight is required',
            'POL.required' => 'Port of lading is required',
            'Remarks.required' => 'Remarks is required',
        ];
    }

    /**
     * @inheritDoc
     */
    public function rules(): array{
        return  [
            'cargo_no'=>'required',
            'cargo_type'=>'required',
            'seal_no'=>'required',
            'b_l_number'=>'required',
            'consignee'=>'required',
            'shipper'=>'required',
            'notify'=>'required',
            'container_type'=>'required',
            'weight'=>'required',
            'pol'=>'required',
            'remarks'=>'required',
        ];
    }
}
