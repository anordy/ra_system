<?php

namespace App\Console\Commands;

use Exception;
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
    protected $signature = 'daily:tax_change';

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
        Log::channel('taxEffectiveDate')->info('Daily Tax Type Change Effective Date Marking process started');
        $this->initEffectiveDate();
        Log::channel('taxEffectiveDate')->info('Daily Tax Type Change Effective Date Marking process ended');
    }

    protected function initEffectiveDate()
    {
        DB::beginTransaction();

        $tax_type_changes = BusinessTaxTypeChange::where('status', 'approved')
            ->whereRaw("TIMESTAMPDIFF(DAY, business_tax_type_changes.effective_date, CURDATE()) = 0")
            ->get();

        try {

            foreach ($tax_type_changes as $tax_change) {

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

            }

            DB::commit();
            Log::channel('taxEffectiveDate')->info('Daily Tax Type Change Effective Date Marking process ended');
        } catch (Exception $e) {
            Log::channel('taxEffectiveDate')->info('Daily Tax Type Change Effective Date Marking process ended with an error');
            Log::channel('taxEffectiveDate')->error($e);
            DB::rollBack();
        }
    }
}
