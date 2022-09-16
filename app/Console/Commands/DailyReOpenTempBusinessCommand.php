<?php

namespace App\Console\Commands;

use App\Models\BranchStatus;
use App\Models\BusinessLocation;
use App\Models\BusinessStatus;
use App\Models\BusinessTempClosure;
use Carbon\Carbon;
use Exception;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

class DailyReOpenTempBusinessCommand extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'daily:reopen-business';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Daily reopen Business Calculations';

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
        Log::channel('reopenBusiness')->info('Daily reopen business process started');
        $this->reopenTempClosedBusinesses();
        Log::channel('reopenBusiness')->info('Daily Debt collection ended');
    }

    /**
     * This job re-opens temporary closed business or locations
     */
    protected function reopenTempClosedBusinesses()
    {

        $closed_businesses = BusinessTempClosure::where('status', 'approved')
            ->whereRaw("TIMESTAMPDIFF(DAY, business_temp_closures.opening_date, CURDATE()) = 0")
            ->get();

        DB::beginTransaction();

        try {

            foreach ($closed_businesses as $closed_business) {

                if ($closed_business->closure_type == 'all') {
                    // Update main business
                    $closed_business->business->status = BusinessStatus::APPROVED;
                    $closed_business->business->save();

                    // Update business locations
                    $locations = $closed_business->business->locations;
                    foreach ($locations as $location) {
                        $location->status = BranchStatus::APPROVED;
                        $location->save();
                    }

                }

                if ($closed_business->closure_type == 'location') {
                    $location = BusinessLocation::findOrFail($closed_business->location_id);
                    $location->status = BranchStatus::APPROVED;
                    $location->save();
                }

                $closed_business->status = 'reopened';
                $closed_business->reopening_date = Carbon::now()->toDateString();
                $closed_business->save();
            
            }

            DB::commit();
            Log::channel('reopenBusiness')->info("Daily reopen business process ended");
        } catch(Exception $e) {
            Log::channel('reopenBusiness')->info('Daily reopen business process ended with error');
            Log::channel('reopenBusiness')->error($e);
            DB::rollBack();
        }

        
    }
}
