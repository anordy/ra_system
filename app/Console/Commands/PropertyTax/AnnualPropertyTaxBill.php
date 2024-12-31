<?php

namespace App\Console\Commands\PropertyTax;

use App\Enum\BillStatus;
use App\Enum\GeneralConstant;
use App\Enum\PropertyPaymentCategoryStatus;
use App\Enum\PropertyStatus;
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
    protected $description = 'Generate property tax bill';

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
        Log::channel('property-tax')->info('Annual Generate Property Tax Bill Start');
        $year = Carbon::now()->year;
        $this->generateBills($year);
        Log::channel('property-tax')->info('Annual Generate Property Tax Bill End');
    }

    public function generateBills($year)
    {
        $currentFinancialYear = FinancialYear::select('id', 'code')
            ->where('code', $year)
            ->firstOrFail();

        Property::query()
            ->where('status', PropertyStatus::APPROVED)
            ->chunk(100, function ($properties) use ($currentFinancialYear) {
                foreach ($properties as $property) {
                    $doesPaymentExist = PropertyPayment::query()
                        ->select('id')
                        ->where('property_ud', $property->id)
                        ->where('financial_year_id', $currentFinancialYear->id)
                        ->first();

                    if (!$doesPaymentExist) {
                        $this->generateBill($property, $currentFinancialYear->id);
                    }

                    unset($property);
                }
            });
    }

    protected function generateBill($property, $financialYearId)
    {

        try {
            $amount = $this->getPayableAmount($property);

            $propertyPayment = PropertyPayment::create([
                'property_id' => $property->id,
                'financial_year_id' => $financialYearId,
                'currency_id' => Currency::where('iso', Currency::TZS)->firstOrFail()->id,
                'amount' => $amount,
                'interest' => GeneralConstant::ZERO_INT,
                'total_amount' => $amount,
                'payment_date' => Carbon::now()->endOfYear()->endOfDay(),
                'curr_payment_date' => Carbon::now()->endOfYear()->endOfDay(),
                'payment_status' => BillStatus::SUBMITTED,
                'payment_category' => PropertyPaymentCategoryStatus::NORMAL,
            ]);

            if (!$propertyPayment) throw new Exception('Failed to save Property Payment');

            GeneratePropertyTaxControlNo::dispatch($propertyPayment);

        } catch (Exception $exception) {
            Log::channel('property-tax')->error($exception);
        }


    }


}
