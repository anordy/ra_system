<?php

namespace App\Imports\Tra;

use App\Models\Tra\TraVehicleMake;
use App\Models\Tra\TraVehicleModelNumber;
use App\Models\Tra\TraVehicleModelType;
use Maatwebsite\Excel\Concerns\Importable;
use Maatwebsite\Excel\Concerns\SkipsEmptyRows;
use Maatwebsite\Excel\Concerns\ToModel;
use Maatwebsite\Excel\Concerns\WithHeadingRow;

class ModelNumberImport implements ToModel, WithHeadingRow, SkipsEmptyRows
{

    use Importable;

    public function model(array $row)
    {
        $make = TraVehicleMake::select('id')->where('code', $row['vehicle_maker_code'])->first();
        $model = TraVehicleModelType::select('id')->where('code', $row['vehicle_model_type_code'])->first();

        TraVehicleModelNumber::updateOrCreate([
            'code' => $row['vehicle_model_number_code'],
        ], [
            'code' => $row['vehicle_model_number_code'],
            'name' => $row['vehicle_model_number_code_description'] == '' ? 'N/A' : $row['vehicle_model_number_code_description'],
            'tra_vehicle_make_id' => $make->id ?? null,
            'tra_vehicle_model_type_id' => $model->id ?? null
        ]);
    }
}
