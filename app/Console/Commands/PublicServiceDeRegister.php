<?php

namespace App\Console\Commands;

use App\Enum\PublicService\DeRegistrationStatus;
use App\Enum\PublicServiceMotorStatus;
use App\Events\SendSms;
use App\Jobs\SendCustomSMS;
use App\Models\PublicService\DeRegistration;
use App\Models\PublicService\PublicServiceMotor;
use Carbon\Carbon;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\DB;

class PublicServiceDeRegister extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'app:ps-de-register';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'De-register approved public services.';

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
        $this->line('Starting check for PS to de-register.');

        // Find them
        $deRegistrations = DeRegistration::query()
            ->select([
                DB::raw('public_service_motors.id as motor_id'),
                'de_registration_date',
            ])
            ->leftJoin('public_service_motors', 'public_service_de_registrations.public_service_motor_id', 'public_service_motors.id')
            ->where('de_registration_date', '<', Carbon::now()->toDateString())
            ->where('public_service_motors.status', [
                PublicServiceMotorStatus::REGISTERED,
                PublicServiceMotorStatus::TEMP_CLOSED
            ])
            ->where('public_service_de_registrations.status', DeRegistrationStatus::APPROVED)
            ->get();

        $this->line( $deRegistrations->count() . ' public services will be de-registered.');

        foreach ($deRegistrations as $deRegistration) {
            $publicMotor = PublicServiceMotor::find($deRegistration->motor_id);

            if (!$publicMotor){
                $this->error("Public service w/ ID $deRegistration->motor_id not found");
                continue;
            }

            $state = $publicMotor->update(['status' => PublicServiceMotorStatus::DEREGISTERED]);

            if ($state){
                $this->info("De-registered {$publicMotor->mvr->plate_number}");

                // Send notification.
                if ($publicMotor->taxpayer->mobile) {
                    event(new SendSms(SendCustomSMS::SERVICE, NULL, [
                        'phone' => $publicMotor->taxpayer->mobile,
                        'message' => "Hello {$publicMotor->taxpayer->fullname}, your public service registration of {$publicMotor->mvr->plate_number} has been de-registered."
                    ]));
                }
            } else {
                $this->error("Failed to de-register {$publicMotor->mvr->plate_number}");
            }
        }

        $this->line('Completed de-registering services.');

        return 0;
    }
}
