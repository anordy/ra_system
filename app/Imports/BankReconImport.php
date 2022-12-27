<?php

namespace App\Imports;

use App\Models\BankRecon;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\Log;
use Maatwebsite\Excel\Concerns\SkipsEmptyRows;
use Maatwebsite\Excel\Concerns\ToCollection;
use Maatwebsite\Excel\Concerns\WithHeadingRow;
use Maatwebsite\Excel\Concerns\WithValidation;

class BankReconImport implements ToCollection, WithHeadingRow, WithValidation, SkipsEmptyRows
{
    public function model(array $row): BankRecon
    {
        Log::info('Importing');
        return new BankRecon();

//        return new BankRecon([
//            'transaction_date' => $row['Transaction Date'],
//            'actual_transaction_date' => $row['Actual Transaction Date'],
//            'transaction_type' => $row['model'],
//            'serial_no' => $row['serial_no'],
//            'imei' => $row['imei'],
//            'created_by' => Auth::id()
//        ]);
    }

    public function collection(Collection $collection)
    {
        foreach ($collection as $key => $row) {
            $eText = $row->get('explanation_text');

            if (str_contains($eText, 'TAX BANK')){
                // Dealing with tax bank
                $exploded = explode('/', $eText);
                if (count($exploded) != 8){ // Add for four
                    Log::info('Skipping row ' . $key);
                    Log::info($exploded);
                    return;
                }

                // Save to DB.
                Log::info([
                    'POS Device' => $exploded[2],
                    'Control No.' => $exploded[3],
                    'Ref No' => $exploded[4],
                    'Payer Name' => $exploded[7]
                ]);
            } elseif (str_contains($eText, 'CASH DEPOSIT')){

            } elseif (str_contains($eText, 'PG-Transfer B2B')){

            } elseif(str_contains($eText, 'PG-Transfer B2B')) {

            }
            else {

            }
        }
    }

    public function rules(): array {
        return  [
            'explanation_text' => 'required',
            'transaction_date' => 'required',
            'actual_transaction_date' => 'required'
        ];
    }

    public function customValidationMessages(): array
    {
        return [
            'actual_transaction_date.required' => 'The actual transaction date is required.',
        ];
    }
}
