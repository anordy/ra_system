<?php

namespace App\Imports;

use App\Enum\BankReconStatus;
use App\Models\BankRecon;
use App\Models\MissingBankRecon;
use App\Models\ZmBill;
use Carbon\Carbon;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\Log;
use Maatwebsite\Excel\Concerns\SkipsEmptyRows;
use Maatwebsite\Excel\Concerns\ToCollection;
use Maatwebsite\Excel\Concerns\WithHeadingRow;
use Maatwebsite\Excel\Concerns\WithValidation;

class BankReconImport implements ToCollection, WithHeadingRow, WithValidation, SkipsEmptyRows
{
    private $currency;

    public function __construct($currency){
        $this->currency = $currency;
    }
    public function model(array $row): BankRecon
    {
        Log::info('Importing');
        return new BankRecon();
    }

    public function collection(Collection $collection)
    {
        /*
        Plans to improve query but increase processing power.
        1. Process all information and save to array with control no as key of each data.
        2. Pluck control no from the array
        3. Find all control no's where in the plucked array
        4. Save the processed information using control no as the key.
        */
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

                    // Compare control No's and save only if exists;
                    if(ZmBill::where('control_number', $exploded[3])->exists()){
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
                            'currency' => $this->currency
                        ]);
                    } else {
                        MissingBankRecon::create([
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
                            'currency' => $this->currency
                        ]);
                    }
                    continue;
                }

                if (count($exploded) == 4){ // Add for four
                    // Index 1 => Control NO
                    // Index 2 => Payer Name
                    // Index 3 => Reference No

                    if(ZmBill::where('control_number', $exploded[1])->exists()) {
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
                            'currency' => $this->currency
                        ]);
                    } else {
                        MissingBankRecon::create([
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
                            'currency' => $this->currency
                        ]);
                    }
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
                        Log::error('Could not obtain Ref No and Bank Branch, format may be different.');
                        Log::info($exploded);
                        continue;
                    }

                    if(ZmBill::where('control_number', $exploded[1])->exists()) {
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
                            'currency' => $this->currency
                        ]);
                    } else {
                        MissingBankRecon::create([
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
                            'currency' => $this->currency
                        ]);
                    }
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
                    if(ZmBill::where('control_number', $exploded[1])->exists()) {
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
                            'currency' => $this->currency
                        ]);
                    } else {
                        MissingBankRecon::create([
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
                            'currency' => $this->currency
                        ]);
                    }
                    continue;
                }

                Log::info('Skipping row ' . $key);
                Log::info($exploded);

                // Alternative to dispatching a job.
                // if (isset($recon) && $recon){
                //    $this->reconcile($recon);
                //}
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
            'dccr' => 'nullable',
            'doc_num' => 'nullable'
        ];
    }

    public function customValidationMessages(): array
    {
        return [
            'actual_transaction_date.required' => 'The actual transaction date is required.',
        ];
    }

    public function reconcile(BankRecon $recon){
        if (!$recon->bill){
            // Update to indicate recon not found.
            $recon->update([
                'is_reconciled' => true,
                'recon_status' => BankReconStatus::NOT_FOUND
            ]);
            return;
        }

        if ($recon->credit_amount < $recon->bill->amount){
            // Update to indicate recon amount mismatch.
            $recon->update([
                'is_reconciled' => true,
                'recon_status' => BankReconStatus::AMOUNT_MISMATCH
            ]);
            $recon->bill->update(['bank_recon_status' => BankReconStatus::AMOUNT_MISMATCH]);
            return;
        }

        if ($recon->credit_amount >= $recon->bill->amount){
            // Update recon to success
            $recon->update([
                'is_reconciled' => true,
                'recon_status' => BankReconStatus::SUCCESS
            ]);
            $recon->bill->update(['bank_recon_status' => BankReconStatus::SUCCESS]);
            return;
        }

        $recon->update([
            'is_reconciled' => true,
            'recon_status' => BankReconStatus::FAILED
        ]);
        $recon->bill->update(['bank_recon_status' => BankReconStatus::FAILED]);
    }
}
