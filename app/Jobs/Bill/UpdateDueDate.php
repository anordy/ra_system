<?php

namespace App\Jobs\Bill;

use App\Models\FinancialMonth;
use App\Models\Returns\ReturnStatus;
use App\Models\Returns\TaxReturn;
use App\Traits\PaymentsTrait;
use Carbon\Carbon;
use Exception;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\Log;

class UpdateDueDate implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels, PaymentsTrait;

    public $updatedFinancialMonth;

    /**
     * Create a new job instance.
     *
     * @return void
     */
    public function __construct($updatedFinancialMonth)
    {
        $this->updatedFinancialMonth = $updatedFinancialMonth;
    }

    /**
     * Execute the job.
     *
     * @return void
     */
    public function handle()
    {
        try {
            $date = Carbon::create($this->updatedFinancialMonth->due_date)->startOfMonth()->subDay();
            $financialMonth = $this->getFinancialMonthFromDate($date);

            // Take all returns which are unpaid
            $taxReturns = TaxReturn::query()
                ->where('financial_month_id', $financialMonth->id)
                ->whereNotIn('payment_status', [ReturnStatus::COMPLETE])
                ->get();

            foreach ($taxReturns as $return) {
                $return->curr_payment_due_date = $this->updatedFinancialMonth->due_date;
                $return->save();

                $latestBill = $return->latestBill;

                if ($latestBill) {
                    $this->updateBill($latestBill, $return->curr_payment_due_date);
                }
            }
        } catch (Exception $exception) {
            Log::error('FAILED TO UPDATE DUE DATE JOB', [$exception]);
        }

    }

    public function getFinancialMonthFromDate($date)
    {
        try {
            $date = Carbon::create($date);
            return FinancialMonth::select('id', 'name', 'financial_year_id', 'due_date', 'number')
                ->whereRaw('EXTRACT(YEAR FROM due_date) = ' . $date->year . '')
                ->whereRaw('EXTRACT(MONTH FROM due_date)  = ' . $date->month . ' ')
                ->firstOrFail();
        } catch (Exception $exception) {
            Log::error('EDIT-FINANCIAL-MONTH-GET-NEXT-FINANCIAL-MONTH-DUE-DATE-FROM-DATE', [$exception]);
            throw $exception;
        }
    }
}
