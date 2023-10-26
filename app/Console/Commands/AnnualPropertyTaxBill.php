<?php

namespace App\Console\Commands;

use App\Enum\BillStatus;
use App\Enum\PropertyPaymentCategoryStatus;
use App\Enum\PropertyStatus;
use App\Enum\PropertyTypeStatus;
use App\Jobs\PropertyTax\GeneratePropertyTaxControlNo;
use App\Models\Currency;
use App\Models\FinancialYear;
use App\Models\PropertyTax\Property;
use App\Models\PropertyTax\PropertyPayment;
use App\Traits\PropertyTaxTrait;
use Carbon\Carbon;
use Exception;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Log;

class AnnualPropertyTaxBill extends Command
{
    use PropertyTaxTrait;
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'annual:property-tax-bill';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Generate property tax bill annually';

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
        Log::channel('property-tax')->info('Annual Generate Property Tax Bill');
        $this->generateBills();
        Log::channel('property-tax')->info('Annual Generate Property Tax Bill');
    }

    public function generateBills()
    {
        $properties = Property::where('status', PropertyStatus::APPROVED)->get();

        if ($properties) {
            foreach ($properties as $property) {
                $currentFinancialYear = FinancialYear::where('code', Carbon::now()->year)->firstOrFail();

                $isBillPresent = $property->payments->where('financial_year_id', $currentFinancialYear->id)->get();

                if (!$isBillPresent) {
                    $this->generateBill($property, $currentFinancialYear->id);
                }
            }
        }
    }

    protected function generateBill($property, $financialYearId)
    {

        try {
            $amount = $this->getPayableAmount($property);

            $propertyPayment = PropertyPayment::create([
                'property_id' => $property->id,
                'financial_year_id' => $financialYearId,
                'currency_id' => Currency::where('iso', 'TZS')->firstOrFail()->id,
                'amount' => $amount,
                'interest' => 0,
                'total_amount' => $amount,
                'payment_date' => Carbon::now()->addMonths(3),
                'curr_payment_date' => Carbon::now()->addMonths(3),
                'payment_status' => BillStatus::SUBMITTED,
                'payment_category' => PropertyPaymentCategoryStatus::NORMAL,
            ]);

            GeneratePropertyTaxControlNo::dispatch($propertyPayment);

        } catch (Exception $exception) {
            Log::channel('property-tax')->error($exception);
            throw new Exception('');
        }


    }


}
