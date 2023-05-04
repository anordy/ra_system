<?php

namespace App\Console\Commands;

use App\Models\Business;
use App\Models\BusinessBank;
use App\Models\BusinessPartner;
use App\Models\SystemSetting;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Log;

class DeleteDraftOverSevenDaysBusinesses extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'daily:delete-draft-businesses-exceed-seven-days';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Command description';

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
        Log::channel('dailyJobs')->info('Start Daily delete all draft businesses that exceed seven days from the day they where created');
        $this->dailyDeleteDraftBusiness();
        Log::channel('dailyJobs')->info('End Daily delete all draft businesses that exceed seven days from the day they where created');
    }

    public function dailyDeleteDraftBusiness(){
        $businesses = Business::whereDate('created_at', '<=', now()->subDays(SystemSetting::DURATION_BEFORE_DELETE_DRAFT_BUSINESSES))->get();
        if ($businesses){
            foreach($businesses as $business){
                BusinessPartner::where('business_id', $business->id)->delete();
                BusinessBank::where('business_id', $business->id)->delete();
                $business->delete();
            }
        }
    }
}
