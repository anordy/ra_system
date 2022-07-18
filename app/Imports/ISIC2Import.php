<?php

namespace App\Imports;

use App\Models\ISIC1;
use App\Models\ISIC2;
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

class ISIC2Import implements ToCollection, WithHeadingRow, WithValidation, SkipsEmptyRows
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
                $isic1Id = ISIC1::where('code',$row['il1_code'])->first()->id;
                ISIC2::create([
                    'code' => $row['code'],
                    'description' => $row['description'],
                    'isic1_id' =>$isic1Id,
                ]);
            }
        } catch (Exception $re) {
            DB::rollBack();
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
            'code.unique' => 'The code has already been taken',
            'description.required' => 'Description is required',
            'il1_code.required' => 'ISIC Level 1 Code is required',
        ];
    }

    /**
     * @inheritDoc
     */
    public function rules(): array
    {
        return  [
            'code' => 'required|unique:isic2s,code',
            'description' => 'required',
            'il1_code' => 'required',
        ];
    }
}
