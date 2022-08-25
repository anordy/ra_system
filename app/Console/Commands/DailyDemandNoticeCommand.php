<?php

namespace App\Console\Commands;

use App\Jobs\DemandNotice\SendDemandNoticeEmail;
use App\Models\Debts\Debt;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Log;

class DailyDemandNoticeCommand extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'daily:notice';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Daily Demand Notice';

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
        $this->runDebts();
        Log::channel('demandNotice')->info('Daily Demand notice process ended');
    }

    protected function runDebts()
    {
        Log::channel('demandNotice')->info("Daily Demand notice for returns and assesments");
        $debts = Debt::where('demand_notice_count', '<=3', 3)
                    ->whereNotIn('status', ['completed', 'paid-by-debt'])
                    ->get();

        foreach ($debts as $debt) {
           $this->sendDemandNotice($debt);
        }
       
    }


    public function sendDemandNotice($debt)
    {

        $email = $debt->business->taxpayer->email;

        $payload = [
            'email' => $email,
            'debt' => $debt
        ];

        SendDemandNoticeEmail::dispatch($payload);

    }
}
