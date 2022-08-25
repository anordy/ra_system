<?php

namespace App\Console\Commands;

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

    protected function reopenTempClosedBusinesses()
    {
        $now = Carbon::now();
     
        $closed_businesses = BusinessTempClosure::where('status', 'approved')
                ->where('business_temp_closures.opening_date', '<', $now)->get();

        foreach ($closed_businesses as $closed) {
            Log::channel('reopenBusiness')->info("Daily Reopen business process started");
            DB::beginTransaction();
            try {
                $closed->business->status = BusinessStatus::APPROVED;
                $closed->business->save();
                DB::commit();
                Log::channel('reopenBusiness')->info("Daily reopen business process ended");
            } catch (Exception $e) {
                Log::channel('reopenBusiness')->info('Daily reopen business process ended with error');
                Log::channel('reopenBusiness')->error($e);
                DB::rollBack();
            }
        }

    }



}
