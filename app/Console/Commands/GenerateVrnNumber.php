<?php

namespace App\Console\Commands;

use App\Enum\ApplicationStatus;
use App\Enum\BillStatus;
use App\Enum\InstallmentStatus;
use App\Enum\PaymentMethod;
use App\Models\BusinessLocation;
use App\Models\BusinessTaxType;
use App\Models\Installment\Installment;
use App\Models\Returns\ReturnStatus;
use Carbon\Carbon;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Log;

class GenerateVrnNumber extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'gen:vrn';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Generate missing vrn numbers';

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
        Log::channel('dailyJobs')->info('Generating VRN Numbers');

        $locations = BusinessTaxType::query()
            ->select('business_locations.id as location_id', 'business_tax_type.tax_type_id as tax_type_id', 'business_locations.vrn')
            ->leftJoin('business_locations', 'business_tax_type.business_id', '=', 'business_locations.business_id')
            ->whereNull('business_locations.vrn')
            ->where('business_tax_type.tax_type_id', 1)
            ->get();

        foreach ($locations as $location) {
            $location = BusinessLocation::find($location->location_id);
            $location->generateVrn();
        }

        Log::channel('dailyJobs')->info('Generating VR process ended');
        return 1;
    }

}
