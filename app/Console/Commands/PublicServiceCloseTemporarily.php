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

class PublicServiceCloseTemporarily extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'app:ps-close';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Temporarily close public services';

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
        $this->line('Starting check for PS to close.');

        // Find them
        $closures = TemporaryClosure::query()
            ->select([
                DB::raw('public_service_motors.id as motor_id'),
                DB::raw('public_service_temporary_closures.id as closure_id'),
                'closing_date',
                'opening_date',
                'public_service_motors.status'
            ])
            ->leftJoin('public_service_motors', 'public_service_temporary_closures.public_service_motor_id', 'public_service_motors.id')
            ->where('closing_date', '<=', Carbon::now()->toDateString())
            ->where('opening_date', '>=', Carbon::now()->toDateString())
            ->where('public_service_motors.status', PublicServiceMotorStatus::REGISTERED)
            ->where('public_service_temporary_closures.status', TemporaryClosureStatus::APPROVED)
            ->get();

        $this->line('Should close ' . $closures->count() . ' public services.');

        foreach ($closures as $closure) {
            $this->line("Closing from  {$closure->closing_date->toDateString()} to {$closure->opening_date->toDateString()}");

            $publicMotor = PublicServiceMotor::find($closure->motor_id);

            if (!$publicMotor){
                $this->error("Public service w/ ID $closure->motor_id not found");
                continue;
            }

            $state = $publicMotor->update(['status' => PublicServiceMotorStatus::TEMP_CLOSED]);

            if ($state){
                $this->info("Closed {$publicMotor->mvr->plate_number}");

                // Send notification.
                if ($closure->taxpayer->mobile) {
                    event(new SendSms(SendCustomSMS::SERVICE, NULL, [
                        'phone' => $publicMotor->taxpayer->mobile,
                        'message' => "Hello {$publicMotor->taxpayer->fullname}, your public service temporary closure of {$publicMotor->mvr->plate_number} has ended, Please log in into your account to generate your sticker payment."
                    ]));
                }
            } else {
                $this->error("Failed to close {$publicMotor->mvr->plate_number}");
            }
        }

        $this->line('Completed closing status.');
    }
}
