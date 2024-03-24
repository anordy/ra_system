<?php

namespace App\Console\Commands;

use App\Enum\PublicService\TemporaryClosureStatus;
use App\Enum\PublicServiceMotorStatus;
use App\Models\PublicService\PublicServiceMotor;
use App\Models\PublicService\TemporaryClosure;
use Carbon\Carbon;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\DB;

class ReOpenPublicServices extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'app:re-open-public-services';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Re-open temporary closed public services.';

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
        // Find them
        $closures = TemporaryClosure::query()
            ->select([
                DB::raw('public_service_motors.id as motor_id'),
                DB::raw('public_service_temporary_closures.id as closure_id'),
            ])
            ->leftJoin('public_service_motors', 'public_service_temporary_closures.public_service_motor_id', 'public_service_motors.id')
            ->where('opening_date', '<=', Carbon::now())
            ->where('public_service_motors.status', PublicServiceMotorStatus::TEMP_CLOSED)
            ->where('public_service_temporary_closures.status', TemporaryClosureStatus::APPROVED)
            ->get();

        foreach ($closures as $closure) {
            // Update public service status.
            $publicMotor = PublicServiceMotor::findOrFail($closure->motor_id);
            $publicMotor->update([
                'status' => PublicServiceMotorStatus::REGISTERED
            ]);

            // Update re-open date on temporary closure.
            $temp = TemporaryClosure::findOrFail($closure->closure_id);
            $temp->update([
                're_opening_date' => Carbon::now()->toDateString()
            ]);
        }

        return 0;
    }
}
