<?php

namespace App\Console\Commands;

use App\Enum\ReturnCategory;
use App\Jobs\DemandNotice\SendDemandNoticeEmail;
use App\Jobs\DemandNotice\SendReturnDemandNoticeEmail;
use App\Models\Returns\TaxReturn;
use Carbon\Carbon;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Log;

class DailyReturnDemandNoticeCommand extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'daily:return-notice';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Daily Return Demand Notice';

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
        Log::channel('demandNotice')->info('Daily Demand notice process started');
        $this->runReturnsDemandNotices();
        Log::channel('demandNotice')->info('Daily Demand notice process ended');
    }

    protected function runReturnsDemandNotices()
    {
        Log::channel('demandNotice')->info("Daily Demand notice for tax returns");
        // TODO: Improve query
        $tax_returns = TaxReturn::with('demandNotices')->where('return_category', ReturnCategory::NORMAL)
            ->get();

        foreach ($tax_returns as $tax_return) {
            if (count($tax_return->demandNotices) == 0) {
                $this->sendDemandNotice($tax_return);
            }
        }
    }


    public function sendDemandNotice($return)
    {

        $payload = [
            'return' => $return,
        ];

        $now = Carbon::now();

        SendReturnDemandNoticeEmail::dispatch($payload)->delay($now->addSeconds(30));
    }
}
