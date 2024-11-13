<?php

namespace App\Console\Commands;

use App\Enum\GeneralConstant;
use App\Enum\ReportRegister\RgRegisterType;
use App\Enum\ReportRegister\RgStatus;
use App\Enum\ReportRegister\RgTaskStatus;
use App\Events\SendSms;
use App\Models\ReportRegister\RgRegister;
use App\Models\ReportRegister\RgSettings;
use App\Traits\ReportRegisterTrait;
use Carbon\Carbon;
use Exception;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Log;

class ReportRegisterSchedules extends Command
{

    use ReportRegisterTrait;

    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'rg:schedule';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Mark Report Register as Breached and Remind unresolved Tasks';

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
        Log::channel('rg-registers')->info('Start of breach marking process');
        $this->markBreaches();
        Log::channel('rg-registers')->info('End of breach marking process');

        Log::channel('rg-registers')->info('Start of task reminder process');
        $this->remindTasks();
        Log::channel('rg-registers')->info('End of task reminder process');
    }

    public function markBreaches()
    {
        $breachSettings = RgSettings::where('name', RgSettings::DAYS_TO_BREACH)->first();

        if (!$breachSettings) {
            Log::channel('rg-registers')->warning('No Breach Days Settings Configured');
            return;
        }

        $daysToBreach = (int) $breachSettings->value;

        RgRegister::query()
            ->select(['id', 'start_date', 'resolved_date', 'breach_date', 'is_breached', 'rg_sub_category_id', 'title'])
            ->with(['currentAssigned'])
            ->where('register_type', RgRegisterType::INCIDENT)
            ->where('status', RgStatus::IN_PROGRESS)
            ->where('is_breached', GeneralConstant::ZERO_INT)
            ->chunk(100, function ($registers) use ($daysToBreach)  {
                foreach ($registers as $register) {
                    try {
                        $startDate = Carbon::create($register->currentAssigned->start_date);
                        $breachDate = $startDate->addDays($daysToBreach);

                        // Remind half-way before breach
                        if (Carbon::now()->gt($breachDate->subHours(12))) {
                            $message = "Hello {$register->assigned->fname}, this is a reminder that a task assigned to you with title: {$register->title} will breach in 12 hours";
                            event(new SendSms(\App\Jobs\SendCustomSMS::SERVICE, NULL, [
                                'phone' => $register->assigned->phone,
                                'message' => $message
                            ]));
                        }

                        // Mark as breached completely
                        if (Carbon::now() > $breachDate) {
                            $register->breach_date = now();
                            $register->is_breached = GeneralConstant::ONE_INT;
                            if (!$register->save()) throw new Exception('Failed to Update Breach Status');

                            // Notify the Group
                            $this->notifyBreach($register->rg_sub_category_id, $register->title);
                        }
                    } catch (Exception $exception) {
                        Log::channel('report-register')->error($exception);
                    }

                }
            });
    }
    public function remindTasks()
    {
        RgRegister::query()
            ->select(['id', 'start_date', 'resolved_date', 'breach_date', 'is_breached', 'rg_sub_category_id', 'title', 'assigned_to_id'])
            ->with(['assigned'])
            ->where('register_type', RgRegisterType::TASK)
            ->whereNotIn('status', [RgTaskStatus::CLOSED, RgTaskStatus::CANCELLED])
            ->chunk(100, function ($registers)  {
                foreach ($registers as $register) {
                    try {
                        $startDate = Carbon::create($register->start_date);

                        if (Carbon::now() > $startDate) {
                            $message = "Hello {$register->assigned->fname}, a task assigned to you with title: {$register->title} has passed due date without any action";
                        } else if(Carbon::now()->diffInDays($startDate) == GeneralConstant::ONE_INT) {
                            $message = "Hello {$register->assigned->fname}, this is a reminder that a task assigned to you with title: {$register->title} is due tomorrow";
                        } else {
                            continue;
                        }

                        event(new SendSms(\App\Jobs\SendCustomSMS::SERVICE, NULL, [
                            'phone' => $register->assigned->phone,
                            'message' => $message
                        ]));

                    } catch (Exception $exception) {
                        Log::channel('report-register')->error($exception);
                    }

                }
            });
    }



}
