<?php

namespace App\Imports;

use App\Models\Street;
use App\Models\Ward;
use Maatwebsite\Excel\Concerns\SkipsEmptyRows;
use Maatwebsite\Excel\Concerns\ToModel;
use Maatwebsite\Excel\Concerns\WithHeadingRow;
use Maatwebsite\Excel\Concerns\WithValidation;

class StreetImport implements ToModel, WithHeadingRow, WithValidation, SkipsEmptyRows
{
    public function rules(): array
    {
        return [
            'shehia' => 'required|exists:wards,name',
            'kijijimtaa' => 'required'
        ];
    }

    public function customValidationMessages(): array
    {
        return [
            'shehia.exists' => 'The given ward does not exist.',
        ];
    }

    public function model(array $row)
    {
        return new Street([
            'ward_id' => Ward::where('name', $row['shehia'])->first()->id,
            'name' => $row['kijijimtaa'],
            'is_approved' => true
        ]);
    }
}
