<?php

namespace App\Console\Commands;

use App\Enum\MvrRegistrationStatus;
use App\Enum\MvrTemporaryTransportStatus;
use App\Events\SendSms;
use App\Jobs\SendCustomSMS;
use App\Models\MvrTemporaryTransport;
use App\Models\Role;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Console\Command;

class MarkTempTransportsReturned extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'mvr:transport-returned';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Mark temporary transports as returned';

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
        $this->markReturned();
    }

    private function markReturned() {
        $mvrs = MvrTemporaryTransport::select('id', 'status', 'date_of_return', 'extended_date')
            ->where('status', MvrTemporaryTransportStatus::APPROVED)
            ->where(function ($query) {
                $query->whereNull('extended_date')
                    ->where('date_of_return', '<=', Carbon::now())
                    ->orWhere(function ($subQuery) {
                        $subQuery->whereNotNull('extended_date')
                            ->where('extended_date', '<=', Carbon::now());
                    });
            })
            ->get();

        $roles = Role::where('name', 'like', '%mvr%')
            ->pluck('id');

        $users = User::whereIn('role_id', $roles)->pluck('phone');

        foreach ($mvrs as $mvr) {
            $message = "Hello, motor vehicle with chassis number " . $mvr->chassis->chassis_number . " has not been returned in time. Please proceed with further actions.";

            foreach ($users as $phone) {
                event(new SendSms(SendCustomSMS::SERVICE, NULL, ['phone' => $phone, 'message' => $message]));
            }
        }

        return 1;
    }


}
