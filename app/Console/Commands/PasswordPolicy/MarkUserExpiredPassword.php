<?php

namespace App\Console\Commands\PasswordPolicy;

use App\Models\User;
use Carbon\Carbon;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

class MarkUserExpiredPassword extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'daily:check-user-password-expire';

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
        Log::channel('dailyJobs')->info('Daily Mark User Expired Password started');
        DB::beginTransaction();
        $this->markPassword();
        DB::commit();
        Log::channel('dailyJobs')->info('Daily Mark User Expired Password ended');
    }

    private function markPassword()
    {
        $usersWithExpiredPassword = User::select('id')
            ->whereDate('pass_expired_on', '<=', Carbon::now())
            ->get();
        if (count($usersWithExpiredPassword) > 0) {
            foreach ($usersWithExpiredPassword as $accountWithExpiredPassword) {
                DB::beginTransaction();
                try {
                    $user = User::find($accountWithExpiredPassword->id);
                    $user->is_password_expired = true;
                    $user->save();
                    DB::commit();
                } catch (\Throwable $th) {
                    DB::rollBack();
                    Log::error($th);
                }
            }
        } else {
            Log::channel('dailyJobs')->info('Date '.Carbon::now().': No Record to Mark User Expired Password');
        }
    }
}
