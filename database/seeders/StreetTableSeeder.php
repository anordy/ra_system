<?php

namespace Database\Seeders;

use App\Models\Street;
use App\Models\Ward;
use Illuminate\Database\Seeder;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\Log;
use Maatwebsite\Excel\Concerns\Importable;
use Maatwebsite\Excel\Concerns\ToModel;
use Maatwebsite\Excel\Concerns\WithHeadingRow;
use Maatwebsite\Excel\Concerns\WithValidation;
use Maatwebsite\Excel\Validators\ValidationException;

class StreetTableSeeder extends Seeder implements ToModel, WithHeadingRow, WithValidation
{
    use Importable;
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        try {
            $this->import(public_path('imports/UNGUJA-STREETS.xlsx'));
        } catch (ValidationException $exception){
            foreach ($exception->failures() as $error) {
                Log::error('Error at row ' . $error->row() . '. ' . $error->errors()[0]);
            }
        }
    }

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
