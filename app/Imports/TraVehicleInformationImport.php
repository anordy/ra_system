<?php

namespace App\Imports;


use App\Imports\Tra\ModelNumberImport;
use App\Imports\Tra\ModelTypeImport;
use Maatwebsite\Excel\Concerns\Importable;
use Maatwebsite\Excel\Concerns\WithMultipleSheets;

class TraVehicleInformationImport implements WithMultipleSheets
{
    use Importable;

    public function sheets(): array
    {
        return [
            'Vehicle Model Type Code Definit' => new ModelTypeImport(),
            'Vehicle Model Number Code Defin' => new ModelNumberImport(),
        ];
    }

}
