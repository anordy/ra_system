<?php

namespace App\Imports;

use App\Models\BankRecon;
use Carbon\Carbon;
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
    }

    public function collection(Collection $collection)
    {
        foreach ($collection as $key => $row) {
            $eText = $row->get('explanation_text');

            if (str_contains($eText, 'TAX BANK')){
                // Dealing with tax bank
                $exploded = explode('/', $eText);

                // Saving 8 count format
                if (count($exploded) == 8){
                    // 'POS Device' => $exploded[2],
                    // 'Control No.' => $exploded[3],
                    // 'Ref No' => $exploded[4],
                    // 'Payer Name' => $exploded[7]
                    $recon = BankRecon::create([
                        'transaction_date' => Carbon::createFromFormat('d/m/Y', $row['transaction_date'])->toDateString(),
                        'actual_transaction_date' => Carbon::createFromFormat('d/m/Y', $row['actual_transaction_date'])->toDateString(),
                        'value_date' => Carbon::createFromFormat('d/m/Y', $row['value_date'])->toDateString(),
                        'original_record' => $row['explanation_text'],
                        'transaction_type' => 'Tax Bank',
                        'control_no' => $exploded[3],
                        'payment_ref' => $exploded[4],
                        'payer_name' => $exploded[7],
                        'debit_amount' => floatval(str_replace(',', '', $row['debit_amount'])),
                        'credit_amount' => floatval(str_replace(',', '', $row['credit_amount'])),
                        'current_balance' => floatval(str_replace(',', '', $row['current_balance'])),
                        'dr_cr' => $row['drcr'],
                        'doc_num' => $row['doc_num'],
                    ]);
                    continue;
                }

                if (count($exploded) == 4){ // Add for four
                    // Index 1 => Control NO
                    // Index 2 => Payer Name
                    // Index 3 => Reference No
                    $recon = BankRecon::create([
                        'transaction_date' => Carbon::createFromFormat('d/m/Y', $row['transaction_date'])->toDateString(),
                        'actual_transaction_date' => Carbon::createFromFormat('d/m/Y', $row['actual_transaction_date'])->toDateString(),
                        'value_date' => Carbon::createFromFormat('d/m/Y', $row['value_date'])->toDateString(),
                        'original_record' => $row['explanation_text'],
                        'transaction_type' => 'Tax Bank',
                        'control_no' => $exploded[1],
                        'payment_ref' => $exploded[3],
                        'payer_name' => $exploded[2],
                        'debit_amount' => floatval(str_replace(',', '', $row['debit_amount'])),
                        'credit_amount' => floatval(str_replace(',', '', $row['credit_amount'])),
                        'current_balance' => floatval(str_replace(',', '', $row['current_balance'])),
                        'dr_cr' => $row['drcr'],
                        'doc_num' => $row['doc_num'],
                    ]);

                    continue;
                }

                Log::info('Skipping row ' . $key);
                Log::info($exploded);
            }
            elseif (str_contains($eText, 'CASH DEPOSIT')){
                // Dealing with cash deposit
                $exploded = explode('/', $eText);

                // Handling four parts
                if (count($exploded) == 4){
                    // Index 1 => Control No.
                    // Index 2 => Payer Name
                    // Index 3 => Ref No + Bank Branch (Transaction Origin)

                    $index3 = explode('FROM', $exploded[3]);

                    if (count($index3) != 2){
                        Log::error('Could not obtain Ref No and Bank Branch');
                        Log::info($exploded);
                        continue;
                    }

                    $recon = BankRecon::create([
                        'transaction_date' => Carbon::createFromFormat('d/m/Y', $row['transaction_date'])->toDateString(),
                        'actual_transaction_date' => Carbon::createFromFormat('d/m/Y', $row['actual_transaction_date'])->toDateString(),
                        'value_date' => Carbon::createFromFormat('d/m/Y', $row['value_date'])->toDateString(),
                        'original_record' => $row['explanation_text'],
                        'transaction_type' => 'Cash Deposit',
                        'control_no' => $exploded[1],
                        'payment_ref' => $index3[0],
                        'transaction_origin' => $index3[1],
                        'payer_name' => $exploded[2],
                        'debit_amount' => floatval(str_replace(',', '', $row['debit_amount'])),
                        'credit_amount' => floatval(str_replace(',', '', $row['credit_amount'])),
                        'current_balance' => floatval(str_replace(',', '', $row['current_balance'])),
                        'dr_cr' => $row['drcr'],
                        'doc_num' => $row['doc_num'],
                    ]);
                    continue;
                }

                Log::info('Skipping row ' . $key);
                Log::info($exploded);
            }
            elseif (str_contains($eText, 'PG-Transfer B2B')){
                // Dealing with PG Transfer
                $exploded = explode('/', $eText);

                // Index 1 => Control No
                // Index 3 => Ref No substr(4)
                if (count($exploded) == 4){
                    $recon = BankRecon::create([
                        'transaction_date' => Carbon::createFromFormat('d/m/Y', $row['transaction_date'])->toDateString(),
                        'actual_transaction_date' => Carbon::createFromFormat('d/m/Y', $row['actual_transaction_date'])->toDateString(),
                        'value_date' => Carbon::createFromFormat('d/m/Y', $row['value_date'])->toDateString(),
                        'original_record' => $row['explanation_text'],
                        'transaction_type' => 'PG Transfer',
                        'control_no' => $exploded[1],
                        'payment_ref' => substr($exploded[3], 4),
                        'debit_amount' => floatval(str_replace(',', '', $row['debit_amount'])),
                        'credit_amount' => floatval(str_replace(',', '', $row['credit_amount'])),
                        'current_balance' => floatval(str_replace(',', '', $row['current_balance'])),
                        'dr_cr' => $row['drcr'],
                        'doc_num' => $row['doc_num'],
                    ]);
                    continue;
                }

                Log::info('Skipping row ' . $key);
                Log::info($exploded);
            }
            else {
                Log::info('Skipping unhandled row ' . $key);
                Log::info($eText);
            }
        }
    }

    public function rules(): array {
        return  [
            'transaction_date' => 'required',
            'actual_transaction_date' => 'required_unless:explanation_text,BALANCE B/F',
            'explanation_text' => 'required',
            'debit_amount' => 'nullable',
            'credit_amount' => 'required_unless:explanation_text,BALANCE B/F',
            'current_balance' => 'required',
            'value_date' => 'required_unless:explanation_text,BALANCE B/F',
            'dc_cr' => 'nullable',
            'doc_num' => 'nullable'
        ];
    }

    public function customValidationMessages(): array
    {
        return [
            'actual_transaction_date.required' => 'The actual transaction date is required.',
        ];
    }
}
