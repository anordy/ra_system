<?php

namespace App\Console\Commands\PasswordPolicy;

use App\Models\Taxpayer;
use Carbon\Carbon;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

class MarkTaxpayerExpiredPassword extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'daily:check-taxpayer-password-expire';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Check taxpayer password expire';

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
        Log::channel('dailyJobs')->info('Daily Mark Taxpayer Expired Password started');
        DB::beginTransaction();
        $this->markPassword();
        DB::commit();
        Log::channel('dailyJobs')->info('Daily Mark Taxpayer Expired Password ended');
    }

    private function markPassword()
    {
        $taxpayersWithExpiredPassword = Taxpayer::select('id')
            ->whereDate('pass_expired_on', '<=', Carbon::now())
            ->get();
        if (count($taxpayersWithExpiredPassword) > 0) {
            foreach ($taxpayersWithExpiredPassword as $accountWithExpiredPassword) {
                DB::beginTransaction();
                try {
                    $taxpayer = Taxpayer::find($accountWithExpiredPassword->id);
                    $taxpayer->is_password_expired = true;
                    $taxpayer->save();
                    DB::commit();
                } catch (\Throwable $th) {
                    DB::rollBack();
                    Log::error($th);
                }
            }
        } else {
            Log::channel('dailyJobs')->info('Date '.Carbon::now().': No Record to Mark Taxpayer Expired Password');
        }
    }
}
