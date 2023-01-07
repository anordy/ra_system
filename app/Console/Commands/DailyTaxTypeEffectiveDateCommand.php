<?php

namespace App\Console\Commands;

use App\Jobs\Business\Taxtype\SendTaxTypeMail;
use Exception;
use Carbon\Carbon;
use App\Models\BusinessTaxType;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use App\Models\BusinessTaxTypeChange;

class DailyTaxTypeEffectiveDateCommand extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'daily:tax-effective-date';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Daily Tax Change Effective Date';

    /**
     * Create a new command instance.
     *
     * @return void
     */
    public function __construct()
    {
        parent::__construct();
    }

    /**
     * Execute the console command.
     *
     * @return int
     */
    public function handle()
    {
        Log::channel('dailyJobs')->info('Daily Tax Type Change Effective Date Marking process started');
        $this->initEffectiveDate();
        Log::channel('dailyJobs')->info('Daily Tax Type Change Effective Date Marking process ended');
    }

    protected function initEffectiveDate()
    {
        $tax_type_changes = BusinessTaxTypeChange::where('status', 'approved')
            ->whereRaw("CURRENT_DATE - CAST(effective_date as date) > 0")
            ->get();


        foreach ($tax_type_changes as $tax_change) {
            DB::beginTransaction();
            try {
                $current_tax_type = BusinessTaxType::where('business_id', $tax_change->business_id)
                    ->where('tax_type_id', $tax_change->from_tax_type_id)
                    ->firstOrFail();

                $current_tax_type->update([
                    'tax_type_id' => $tax_change->to_tax_type_id,
                    'currency' => $tax_change->to_tax_type_currency,
                ]);

                $tax_change->update([
                    'status' => 'effective'
                ]);

                $payload = [
                    'tax_type' => $current_tax_type,
                    'tax_change' => $tax_change,
                    'time' => Carbon::now()->format('d-m-Y')
                ];
                DB::commit();
                $this->sendTaxChangeEmail($payload);
            } catch (Exception $e) {
                DB::rollBack();
                Log::channel('dailyJobs')->info('Daily Tax Type Change Effective Date Marking process ended with an error');
                Log::channel('dailyJobs')->error($e);
            }

            Log::channel('dailyJobs')->info('Daily Tax Type Change Effective Date Marking process ended');
        }
    }

    public function sendTaxChangeEmail($payload)
    {
        SendTaxTypeMail::dispatch($payload);
    }
}
