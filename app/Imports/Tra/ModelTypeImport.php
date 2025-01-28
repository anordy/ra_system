<?php

namespace App\Imports\Tra;

use App\Models\Tra\TraVehicleMake;
use App\Models\Tra\TraVehicleModelType;
use Maatwebsite\Excel\Concerns\Importable;
use Maatwebsite\Excel\Concerns\SkipsEmptyRows;
use Maatwebsite\Excel\Concerns\ToModel;
use Maatwebsite\Excel\Concerns\WithHeadingRow;

class ModelTypeImport implements ToModel, WithHeadingRow, SkipsEmptyRows
{
    use Importable;

    public function model(array $row)
    {
        $make = TraVehicleMake::select('id')->where('code', $row['vehicle_maker_code'])->first();

        TraVehicleModelType::updateOrCreate([
            'code' => $row['vehicle_model_type_code'],
        ], [
            'code' => $row['vehicle_model_type_code'],
            'name' => $row['vehicle_model_type_code_description'] == '' ? 'N/A' : $row['vehicle_model_type_code_description'],
            'tra_vehicle_make_id' => $make->id ?? null
        ]);

    }
}
