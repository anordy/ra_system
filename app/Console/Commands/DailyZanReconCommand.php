<?php
namespace App\Console\Commands;

use Carbon\Carbon;
use App\Models\ZmRecon;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Log;
use App\Services\Api\ZanMalipoInternalService;

class DailyZanReconCommand extends Command
{
    public $reconDate;
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'daily:recon';

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
        $this->reconDate = Carbon::today()->subDay(1)->format('Y-m-d');
    }


    /**
     * Execute the console command.
     *
     * @return int
     */
    public function handle()
    {
        Log::channel('dailyJobs')->info("Running Recon Request for {$this->reconDate}");
        $this->runRecon();
        Log::channel('dailyJobs')->info("Recon for {$this->reconDate} ended");
    }

    public function runRecon()
    {
        try{

            $recon = ZmRecon::create([
                'TnxDt' => $this->reconDate,
                'ReconcOpt' => "1"
            ]);

            if ($recon) {
                $enquireRecon = (new ZanMalipoInternalService)->requestRecon($recon->id);
            }
            
        } catch (\Exception $e) {
            Log::channel('dailyJobs')->info("Recon for {$this->reconDate} failed");
            Log::channel('dailyJobs')->error($e);
        }
    }
}

