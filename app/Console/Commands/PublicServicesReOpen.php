<?php

namespace App\Console\Commands;

use App\Enum\PublicService\TemporaryClosureStatus;
use App\Enum\PublicServiceMotorStatus;
use App\Events\SendSms;
use App\Jobs\SendCustomSMS;
use App\Models\PublicService\PublicServiceMotor;
use App\Models\PublicService\TemporaryClosure;
use Carbon\Carbon;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\DB;

class PublicServicesReOpen extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'app:ps-reopen';

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
            ->where('opening_date', '<', Carbon::now()->toDateString())
            ->where('public_service_motors.status', PublicServiceMotorStatus::TEMP_CLOSED)
            ->where('public_service_temporary_closures.status', TemporaryClosureStatus::APPROVED)
            ->get();


        $this->line("Opening {$closures->count()} public service registrations.");

        foreach ($closures as $closure) {
            // Update public service status.
            $publicMotor = PublicServiceMotor::findOrFail($closure->motor_id);
            $pmState = $publicMotor->update([
                'status' => PublicServiceMotorStatus::REGISTERED
            ]);

            // Update re-open date on temporary closure.
            $temp = TemporaryClosure::findOrFail($closure->closure_id);
            $tmpState = $temp->update([
                're_opening_date' => Carbon::now()->toDateString()
            ]);

            if ($pmState && $tmpState){
                $this->line("Opened {$publicMotor->mvr->plate_number}");

                // Send notification.
                if ($publicMotor->taxpayer->mobile) {
                    event(new SendSms(SendCustomSMS::SERVICE, NULL, [
                        'phone' => $publicMotor->taxpayer->mobile,
                        'message' => "Hello {$publicMotor->taxpayer->fullname}, your public service temporary closure of {$publicMotor->mvr->plate_number} has ended, Please log in into your account to generate your sticker payment."
                    ]));
                }
            } else {
                $this->error("Failed to open {$publicMotor->mvr->plate_number}");
            }
        }

        $this->line('Re opening public service completed.');

        return 0;
    }
}
