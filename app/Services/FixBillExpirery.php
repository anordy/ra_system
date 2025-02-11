<?php

namespace App\Services;

use App\Jobs\Bill\CancelBill;
use App\Jobs\Debt\GenerateControlNo;
use App\Models\Audit;
use App\Models\Debts\DebtPenalty;
use App\Models\Returns\TaxReturn;
use App\Traits\TaxpayerLedgerTrait;
use Carbon\Carbon;

class FixBillExpirery
{
    use TaxpayerLedgerTrait;

    public function up($id) {
        $return = TaxReturn::find($id);
        $return->principal = $return->return->petroleum_levy;
        $return->interest = 0;
        $return->total_amount = $return->return->total_amount_due;
        $return->outstanding_amount = $return->return->total_amount_due;
        $return->save();

        $this->updateLedger(TaxReturn::class, $return->id, $return->principal, 0, 0, $return->total_amount);
    }

    public function zp($id) {
        $return = TaxReturn::find($id);

        $return->principal = $return->return->petroleum_levy;
        $return->interest = 0;
        $return->total_amount = $return->return->total_amount_due;
        $return->outstanding_amount = $return->return->total_amount_due;
        $return->save();

        $this->updateLedger(TaxReturn::class, $return->id, $return->principal, 0, 0, $return->total_amount);

        $return = TaxReturn::find($id);

        if ($return->latestBill) {
            CancelBill::dispatch($return, 'Bill Adjustment');
        }

        GenerateControlNo::dispatch($return);
    }

    public function membe($id) {
        $return = TaxReturn::find($id);

        $return->interest = 14700;
        $return->total_amount = 830700;
        $return->outstanding_amount = 830700;

        $return->save();

        DebtPenalty::whereIn('id', ['16611', '19439'])->forceDelete();

        $return = TaxReturn::find($id);

        $this->updateLedger(TaxReturn::class, $return->id, $return->principal, 0, 0, $return->total_amount);

        if ($return->latestBill) {
            CancelBill::dispatch($return, 'Bill Adjustment');
        }

        GenerateControlNo::dispatch($return);

    }

    public function run()
    {

        $handle = fopen('audits.csv', 'w+');

        $header = ['S/N', 'Log Type', 'User Name', 'IP Address', 'Info', 'User Agent', 'Event Time']; // Replace with your actual column names
        fputcsv($handle, $header, ';');

        Audit::query()->select(['id', 'user_type', 'user_id', 'event', 'auditable_id', 'auditable_type', 'ip_address', 'user_agent', 'created_at'])
            ->whereBetween('created_at', [Carbon::create('2024-04-01'), Carbon::create('2024-06-30')])
            ->chunk(500, function ($users) use ($handle) {
                foreach ($users as $i => $row) {
                    $rowArray = $row->toArray();

                    $model = preg_split('[\\\]', $row->auditable_type)[2];
                    $label = preg_replace('/(?<=[a-z])[A-Z]|[A-Z](?=[a-z])/', ' $0', $model);
                    $event = ucfirst($row->event);
                    $fullName = ucfirst($row->user->fullname ?? '-');

                    if ($row->event === 'logged in') {
                        $logType = "{$fullName} {$event}";
                    } else if ($row->event === 'logged out') {
                        $logType = "{$fullName} {$event}";
                    } else {
                        $logType = "{$event} {$label} with id {$row->auditable_id}";
                    }

                    $mappedData = [
                        $i+1,
                        $logType,
                        $fullName,
                        $row->ip_address,
                        '',
                        $row->user_agent,
                        $row->created_at
                    ];


                    fputcsv($handle, $mappedData, ';');
                }
            });

        fclose($handle);

    }

}
