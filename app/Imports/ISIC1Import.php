<?php

namespace App\Imports;

use App\Models\ISIC1;
use Exception;
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

class ISIC1Import implements ToCollection, WithHeadingRow, WithValidation, SkipsEmptyRows
{
    use Importable;
    private $errors = []; // array to accumulate errors
    public function collection(Collection $rows)
    {
        $rows = $rows->toArray();
        DB::beginTransaction();
        try {
            foreach ($rows as $key => $row) {
                $validator = Validator::make($row, $this->rules(), $this->validationMessages());
                if ($validator->fails()) {
                    throw new Exception('Correct the excel');
                }
                ISIC1::updateOrCreate([
                    'code' => $row['code'],
                    'description' => $row['description'],
                ]);
            }
        } catch (Exception $e) {
            DB::rollBack();
            Log::error($e);
        }
        DB::commit();
    }

    // this function returns all validation errors after import:
    public function getErrors()
    {
        return $this->errors;
    }

    public function validationMessages()
    {
        return [
            'code.required' => 'Code is required',
            'description.required' => 'Description is required',
        ];
    }

    /**
     * @inheritDoc
     */
    public function rules(): array
    {
        return  [
            'code' => 'required',
            'description' => 'required',
        ];
    }
}
