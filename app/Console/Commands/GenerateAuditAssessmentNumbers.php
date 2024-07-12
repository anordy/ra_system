<?php

namespace App\Console\Commands;

use App\Models\Investigation\TaxInvestigation;
use App\Models\TaxAudit\TaxAudit;
use App\Services\SequenceGenerator\AuditAssessment;
use Illuminate\Console\Command;

class GenerateAuditAssessmentNumbers extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'app:gaan';

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
        try {
            // Audits
            $audits = TaxAudit::query()
                ->whereNotNull('approved_on')
                ->whereHas('assessment')
                ->whereNull('assessment_number')
                ->get();

            foreach ($audits as $audit) {
                $number = (new AuditAssessment())->generateSequence($audit->location->taxRegion->location);
                $audit->assessment_number = $number;
                !$audit->save() ? $this->error('failed to update') : '';
                $this->info($number);
            }

        } catch (\Exception $exception) {
            $this->error($exception->getMessage());
        }
    }
}
