<?php

namespace App\Http\Livewire\Investigation;

use App\Models\Investigation\TaxInvestigation;
use App\Models\Returns\BFO\BfoConfig;
use App\Models\Returns\BFO\BfoReturnItems;
use App\Models\Returns\EmTransactionConfig;
use App\Models\Returns\EmTransactionReturnItem;
use App\Models\Returns\ExciseDuty\MnoConfig;
use App\Models\Returns\ExciseDuty\MnoReturnItem;
use App\Models\Returns\HotelReturns\HotelReturnConfig;
use App\Models\Returns\HotelReturns\HotelReturnItem;
use App\Models\Returns\LumpSum\LumpSumReturn;
use App\Models\Returns\MmTransferConfig;
use App\Models\Returns\MmTransferReturnItem;
use App\Models\Returns\Petroleum\PetroleumConfig;
use App\Models\Returns\Petroleum\PetroleumReturnItem;
use App\Models\Returns\Port\PortConfig;
use App\Models\Returns\Port\PortReturnItem;
use App\Models\Returns\StampDuty\StampDutyConfig;
use App\Models\Returns\StampDuty\StampDutyReturnItem;
use App\Models\Returns\Vat\VatReturnConfig;
use App\Models\Returns\Vat\VatReturnItem;
use App\Models\TaxType;
use Carbon\Carbon;
use DateTime;
use App\Traits\CustomAlert;
use Illuminate\Support\Facades\Log;
use Livewire\Component;

class DeclaredSalesAnalysis extends Component
{
    use CustomAlert;

    public $modelName;
    public $purchases;
    public $sales;
    public $output_vat;
    public $input_vat;

    public $returns = [];
    public $taxType;
    public $branch;
    public $headersBfo;
    public $headersMno;
    public $headersEmTransaction;
    public $headersMmTransfer;
    public $headersPetroleum;
    public $withoutPurchases = false;
    public $start_date;
    public $end_date;

    public function mount($investigationId, $tax_type_id, $location_id)
    {
        $investigation = TaxInvestigation::find(decrypt($investigationId));
        if (is_null($investigation)) {
            abort(404);
        }
        $this->taxType = $investigation->taxTypes->firstWhere('id', decrypt($tax_type_id));
        $this->branch = $investigation->businessLocations->firstWhere('id', decrypt($location_id));
        $this->start_date = $this->validateDate($investigation->period_from) ? $investigation->period_from : Carbon::now();
        $this->end_date = $this->validateDate($investigation->period_to) ? $investigation->period_to : Carbon::now();

        switch ($this->taxType->code) {
            case TaxType::HOTEL:
            case TaxType::RESTAURANT:
            case TaxType::TOUR_OPERATOR:
            case TaxType::AIRBNB:
                $this->hotel();
                break;
            case TaxType::PETROLEUM:
                $this->petroleum();
                break;
            case TaxType::VAT:
                $this->vat();
                break;
            case TaxType::EXCISE_DUTY_MNO:
                $this->mno();
                break;
            case TaxType::EXCISE_DUTY_BFO:
                $this->bfo();
                break;
            case TaxType::ELECTRONIC_MONEY_TRANSACTION:
                $this->emTransaction();
                break;
            case TaxType::LUMPSUM_PAYMENT:
                $this->lumpSum();
                break;
            case TaxType::MOBILE_MONEY_TRANSFER:
                $this->mmTransfer();
                break;
            case TaxType::STAMP_DUTY:
                $this->stampDuty();
                break;
            case TaxType::AIRPORT_SERVICE_SAFETY_FEE:
                $this->airport();
                break;

            case TaxType::SEAPORT_SERVICE_TRANSPORT_CHARGE:
                $this->sea();
                break;
            default:
                $this->handleDefault();
                break;
        }
    }

    public function validateDate($date, $format = 'Y-m-d')
    {
        $d = DateTime::createFromFormat($format, $date);

        return $d && $d->format($format) === $date;
    }
    protected function handleDefault()
    {
        Log::warning('Unhandled tax type: ' . $this->taxType->code);
    }

    /**
     * Processes hotel data and calculates various metrics based on the sales and purchases data.
     *
     * This method retrieves the sales and purchases data for hotels, processes the data, and calculates
     * metrics such as total sales, total purchases, output VAT, input tax, and tax paid. The results
     * are then sorted by month and grouped by year.
     */
    public function hotel()
    {
        $configs = [
            'purchases' => HotelReturnConfig::PURCHASES,
            'sales' => HotelReturnConfig::SALES,
        ];

        $this->processHotelData($configs);

        $returns = $this->replaceRecursiveArrays($this->purchases, $this->sales);

        $calculations = collect(array_map(function ($returns) {
            return [
                'year' => $returns['year'],
                'month' => $returns['month'],
                'financial_month' => "{$returns['month']} {$returns['year']}",
                'total_sales' => $returns['total_sales'],
                'total_purchases' => $returns['total_purchases'],
                'output_vat' => $returns['total_sales_vat'],
                'input_tax' => $returns['total_purchases_vat'],
                'tax_paid' => (float) ($returns['total_sales_vat']) - (float) $returns['total_purchases_vat']
            ];
        }, $returns));

        $this->returns = $calculations->sortByDesc('month')->groupBy('year');
    }

    /**
     * Processes hotel data based on the provided configurations.
     *
     * This function retrieves hotel return data from the database, grouped by financial year and month, 
     * and sums the total value and VAT for each configuration code specified in the $configs parameter.
     *
     * @param array $configs An array of configuration codes to process.
     * @return void
     */
    private function processHotelData($configs)
    {
        foreach ($configs as $key => $configCodes) {
            $query = HotelReturnItem::selectRaw('financial_months.name as month, financial_years.code as year, SUM(value) as total_' . $key . ', SUM(vat) as total_' . $key . '_vat')
                ->leftJoin('hotel_return_configs', 'hotel_return_configs.id', 'hotel_return_items.config_id')
                ->leftJoin('hotel_returns', 'hotel_returns.id', 'hotel_return_items.return_id')
                ->leftJoin('financial_months', 'financial_months.id', 'hotel_returns.financial_month_id')
                ->leftJoin('financial_years', 'financial_years.id', 'financial_months.financial_year_id')
                ->where('hotel_returns.tax_type_id', $this->taxType->id)
                ->where('hotel_returns.business_location_id', $this->branch->id)
                ->whereIn('config_id', HotelReturnConfig::whereIn('code', $configCodes)->select('id')->pluck('id'))
                ->groupBy(['financial_years.code', 'financial_months.name'])
                ->get();

            $this->$key = $query;
        }
    }


    protected function lumpSum()
    {
        $yearReturnGroup = LumpSumReturn::select('total_amount_due', 'installment', 'quarter_name', 'payment_quarters as return_months', 'total_amount_due_with_penalties', 'quarter as quarter', 'financial_years.name as year')
            ->leftJoin('lump_sum_payments', 'lump_sum_payments.business_id', 'lump_sum_returns.business_id')
            ->leftJoin('financial_years', 'financial_years.id', 'lump_sum_returns.financial_year_id')
            ->get()->groupBy(['year', 'quarter']);

        $yearData = $this->formatQuaters($yearReturnGroup);

        $this->withoutPurchases = true;
        $this->returns = $yearData;
    }

    protected function petroleum()
    {
        $config = PetroleumConfig::select('id', 'name')->where('code', '!=', PetroleumConfig::TOTAL)->get();

        $salesConfigs = $config->pluck('id');
        $headers = $config->pluck('name');

        $yearReturnGroup = PetroleumReturnItem::select('petroleum_configs.code', 'petroleum_return_items.value', 'petroleum_return_items.vat', 'financial_months.name as month', 'financial_years.name as year')
            ->leftJoin('petroleum_configs', 'petroleum_configs.id', 'petroleum_return_items.config_id')
            ->leftJoin('petroleum_returns', 'petroleum_returns.id', 'petroleum_return_items.return_id')
            ->leftJoin('financial_months', 'financial_months.id', 'petroleum_returns.financial_month_id')
            ->leftJoin('financial_years', 'financial_years.id', 'financial_months.financial_year_id')
            ->whereIn('config_id', $salesConfigs)
            ->get()->groupBy(['year', 'month']);

        $yearData = $this->formatDataArray($yearReturnGroup);
//
//        $this->withoutPurchases = true;
        $this->returns = $yearData;
        $this->headersPetroleum = $headers;
    }

    protected function airport()
    {
        $salesConfigs = PortConfig::where('code', '!=', PortConfig::TLATZS)->get()->pluck('id');
        $headers = PortConfig::whereIn('code', PortConfig::AIR_PORT_HEADER_CODES)->get()->pluck('name');

        $yearReturnGroup = PortReturnItem::select('port_configs.code', 'port_configs.currency', 'port_return_items.value', 'port_return_items.vat', 'financial_months.name as month', 'financial_years.name as year')
            ->leftJoin('port_configs', 'port_configs.id', 'port_return_items.config_id')
            ->leftJoin('port_returns', 'port_returns.id', 'port_return_items.return_id')
            ->leftJoin('financial_months', 'financial_months.id', 'port_returns.financial_month_id')
            ->leftJoin('financial_years', 'financial_years.id', 'financial_months.financial_year_id')
            ->whereIn('config_id', $salesConfigs)
            ->get()->groupBy(['year', 'month']);

        $yearData = $this->formatDataArrayPort($yearReturnGroup);

        $this->withoutPurchases = true;
        $this->returns = $yearData;
        $this->headersPort = $headers;
    }

    protected function sea()
    {
        $salesConfigs = PortConfig::where('code', '!=', PortConfig::TLATZS)->get()->pluck('id');
        $headers = PortConfig::whereIn('code', PortConfig::SEA_PORT_HEADER_CODES)->get()->pluck('name');

        $yearReturnGroup = PortReturnItem::select('port_configs.code', 'port_configs.currency', 'port_return_items.value', 'port_return_items.vat', 'financial_months.name as month', 'financial_years.name as year')
            ->leftJoin('port_configs', 'port_configs.id', 'port_return_items.config_id')
            ->leftJoin('port_returns', 'port_returns.id', 'port_return_items.return_id')
            ->leftJoin('financial_months', 'financial_months.id', 'port_returns.financial_month_id')
            ->leftJoin('financial_years', 'financial_years.id', 'financial_months.financial_year_id')
            ->whereIn('config_id', $salesConfigs)
            ->get()->groupBy(['year', 'month']);

        $yearData = $this->formatDataArrayPort($yearReturnGroup);

        $this->withoutPurchases = true;
        $this->returns = $yearData;
        $this->headersPort = $headers;
    }

    public function vat()
    {
        $configs = [
            'purchases' => VatReturnConfig::PURCHASES,
            'sales' => VatReturnConfig::SALES,
        ];

        $this->processVatData($configs);

        $returns = $this->replaceRecursiveArrays($this->purchases, $this->sales);

        $calculations = collect(array_map(function ($returns) {
            return [
                'year' => $returns['year'],
                'month' => $returns['month'],
                'financial_month' => "{$returns['month']} {$returns['year']}",
                'total_sales' => $returns['total_sales'],
                'total_purchases' => $returns['total_purchases'],
                'output_vat' => $returns['total_sales_vat'],
                'input_tax' => $returns['total_purchases_vat'],
                'tax_paid' => (float)($returns['total_sales_vat']) - (float) $returns['total_purchases_vat'],
            ];
        }, $returns));

        $this->returns = $calculations->sortByDesc('month')->groupBy('year');
    }

    private function processVatData($configs)
    {
        foreach ($configs as $key => $configCodes) {
            $query = VatReturnItem::query()->selectRaw('financial_months.name as month, financial_years.code as year, SUM(value) as total_' . $key . ', SUM(vat) as total_' . $key . '_vat')
                ->leftJoin('vat_return_configs', 'vat_return_configs.id', 'vat_return_items.config_id')
                ->leftJoin('vat_returns', 'vat_returns.id', 'vat_return_items.return_id')
                ->leftJoin('financial_months', 'financial_months.id', 'vat_returns.financial_month_id')
                ->leftJoin('financial_years', 'financial_years.id', 'financial_months.financial_year_id')
                ->where('vat_returns.tax_type_id', $this->taxType->id)
                ->where('vat_returns.business_location_id', $this->branch->id)
                ->whereIn('config_id', VatReturnConfig::query()->whereIn('code', $configCodes)->select('id')->pluck('id'))
                ->groupBy(['financial_years.code', 'financial_months.name'])
                ->get();

            $this->$key = $query;
        }
    }


    public function mno()
    {
        $configs = MnoConfig::where('code', '!=', PetroleumConfig::TOTAL)
            ->select('id', 'name') // Select only needed columns
            ->get();

        $salesConfigs = $configs->pluck('id');
        $headers = $configs->pluck('name');


        $yearReturnGroup = MnoReturnItem::select('mno_configs.code', 'mno_return_items.value', 'mno_return_items.vat', 'financial_months.name as month', 'financial_years.name as year')
            ->leftJoin('mno_configs', 'mno_configs.id', 'mno_return_items.mno_config_id')
            ->leftJoin('mno_returns', 'mno_returns.id', 'mno_return_items.mno_return_id')
            ->leftJoin('financial_months', 'financial_months.id', 'mno_returns.financial_month_id')
            ->leftJoin('financial_years', 'financial_years.id', 'financial_months.financial_year_id')
            ->whereIn('mno_config_id', $salesConfigs)
            ->get()->groupBy(['year', 'month']);

        $yearData = $this->formatDataArray($yearReturnGroup);

        $this->withoutPurchases = true;
        $this->returns = $yearData;
        $this->headersMno = $headers;
    }

    protected function bfo()
    {
        $configs = BfoConfig::where('code', '!=', BfoConfig::TotalFBO)->select('id', 'name')->get();
        $salesConfigs = $configs->pluck('id');
        $headers = $configs->pluck('name');

        $yearReturnGroup = BfoReturnItems::select('bfo_configs.code', 'bfo_return_items.value', 'bfo_return_items.vat', 'financial_months.name as month', 'financial_years.name as year')
            ->leftJoin('bfo_configs', 'bfo_configs.id', 'bfo_return_items.config_id')
            ->leftJoin('bfo_returns', 'bfo_returns.id', 'bfo_return_items.return_id')
            ->leftJoin('financial_months', 'financial_months.id', 'bfo_returns.financial_month_id')
            ->leftJoin('financial_years', 'financial_years.id', 'financial_months.financial_year_id')
            ->whereIn('config_id', $salesConfigs)
            ->get()->groupBy(['year', 'month']);

        $yearData = $this->formatDataArray($yearReturnGroup);

        $this->withoutPurchases = true;
        $this->returns = $yearData;
        $this->headersBfo = $headers;
    }

    protected function emTransaction()
    {
        $configs = EmTransactionConfig::where('code', '!=', EmTransactionConfig::TotalEMT)->select('id', 'name')->get();
        $salesConfigs = $configs->pluck('id');
        $headers = $configs->pluck('name');

        $yearReturnGroup = EmTransactionReturnItem::select('em_transaction_configs.code', 'em_transaction_return_items.value', 'em_transaction_return_items.vat', 'seven_days_financial_months.name as month', 'financial_years.name as year')
            ->leftJoin('em_transaction_configs', 'em_transaction_configs.id', 'em_transaction_return_items.config_id')
            ->leftJoin('em_transaction_returns', 'em_transaction_returns.id', 'em_transaction_return_items.return_id')
            ->leftJoin('seven_days_financial_months', 'seven_days_financial_months.id', 'em_transaction_returns.financial_month_id')
            ->leftJoin('financial_years', 'financial_years.id', 'seven_days_financial_months.financial_year_id')
            ->whereIn('config_id', $salesConfigs)
            ->get()->groupBy(['year', 'month']);

        $yearData = $this->formatDataArray($yearReturnGroup);

        $this->withoutPurchases = true;
        $this->returns = $yearData;
        $this->headersEmTransaction = $headers;
    }

    protected function mmTransfer()
    {
        $configs = MmTransferConfig::where('code', '!=', EmTransactionConfig::TotalEMT)->select('id', 'name')->get();
        $salesConfigs = $configs->pluck('id');
        $headers = $configs->pluck('name');

        $yearReturnGroup = MmTransferReturnItem::select('mm_transfer_configs.code', 'mm_transfer_return_items.value', 'mm_transfer_return_items.vat', 'seven_days_financial_months.name as month', 'financial_years.name as year')
            ->leftJoin('mm_transfer_configs', 'mm_transfer_configs.id', 'mm_transfer_return_items.config_id')
            ->leftJoin('mm_transfer_returns', 'mm_transfer_returns.id', 'mm_transfer_return_items.return_id')
            ->leftJoin('seven_days_financial_months', 'seven_days_financial_months.id', 'mm_transfer_returns.financial_month_id')
            ->leftJoin('financial_years', 'financial_years.id', 'seven_days_financial_months.financial_year_id')
            ->whereIn('config_id', $salesConfigs)
            ->get()->groupBy(['year', 'month']);

        $yearData = $this->formatDataArray($yearReturnGroup);

        $this->withoutPurchases = true;
        $this->returns = $yearData;
        $this->headersMmTransfer = $headers;
    }

    protected function formatDataArray($yearReturnGroup)
    {
        $yearData = [];
        foreach ($yearReturnGroup as $keyYear => $monthreturnGroup) {
            $monthreturnGroup = $monthreturnGroup->toArray();
            $monthData = array_map(function ($returnItems) use ($keyYear) {
                $itemValue = [
                    'month' => array_column($returnItems, 'month')[0],
                    'totalValue' => array_sum(array_column($returnItems, 'value')),
                    'totalVat' => array_sum(array_column($returnItems, 'vat')),
                ];
                foreach ($returnItems as $keyItem => $item) {
                    $itemValue[$item['code']] = $item['value'];
                }
                return $itemValue;
            }, $monthreturnGroup);
            $yearData[$keyYear] = $monthData;
        }

        return $yearData;
    }


    protected function formatDataArrayPort($yearReturnGroup)
    {
        $yearData = [];
        foreach ($yearReturnGroup as $keyYear => $monthreturnGroup) {
            $monthreturnGroup = $monthreturnGroup->toArray();
            $monthData = array_map(function ($returnItems) {
                $itemValue = [
                    'month' => array_column($returnItems, 'month')[0],
                    'totalValue' => array_sum(array_column($returnItems, 'value')),
                ];

                return $returnItems->reduce(function ($carry, $item) use (&$itemValue) {
                    $itemValue[$item['code']] = $item['value'] ?? null;
                    $carry['totalVat' . $item['currency']] = ($carry['totalVat' . $item['currency']] ?? 0) + $item['vat'];
                    return $carry;
                }, $itemValue);
            }, $monthreturnGroup);
            $yearData[$keyYear] = $monthData;
        }

        return $yearData;
    }


    protected function formatQuaters($yearReturnGroup)
    {
        $yearData = [];
        foreach ($yearReturnGroup as $keyYear => $quaterReturnGroup) {
            $quaterReturnGroup = $quaterReturnGroup->toArray();
            $quarterData = array_map(function ($returnItems) {
//                dd(array_column($returnItems, 'return_months'));
                $itemValue = [
                    'quarter' => array_column($returnItems, 'return_months'),
                    'installment' => array_column($returnItems, 'installment'),
                    'quarter_name' => array_column($returnItems, 'quarter_name'),
                    'amountWithPenalties' => array_column($returnItems, 'total_amount_due_with_penalties'),
                    'principalAmount' => array_column($returnItems, 'total_amount_due'),
                    'Penalties' => (float)array_column($returnItems, 'total_amount_due_with_penalties') - (float)array_column($returnItems, 'total_amount_due'),
                ];
                return $itemValue;
            }, $quaterReturnGroup);
            $yearData[$keyYear] = $quarterData;
        }

        return $yearData;
    }

    public function stampDuty()
    {
        $configs = [
            'purchases' => StampDutyConfig::STAMP_DUTY_CODES,
            'sales' => StampDutyConfig::STAMP_DUTY_SALES_CODES,
        ];

        $this->processStampDutyData($configs);

        $returns = $this->replaceRecursiveArrays($this->purchases, $this->sales);

        // dd($returns);


        $calculations = collect(array_map(function ($returns) {
            return [
                'year' => $returns['year'],
                'month' => $returns['month'],
                'financial_month' => "{$returns['month']} {$returns['year']}",
                'total_sales' => $returns['total_sales'],
                'total_purchases' => $returns['total_purchases'],
                'output_vat' => $returns['total_sales_vat'],
                'input_tax' => $returns['total_purchases_vat'],
                'tax_paid' => (float)($returns['total_sales_vat']) - (float)$returns['total_purchases_vat'],
            ];
        }, $returns));

        $this->returns = $calculations->sortByDesc('month')->groupBy('year');
    }

    private function processStampDutyData($configs)
    {
        foreach ($configs as $key => $configCodes) {
            $query = StampDutyReturnItem::selectRaw('financial_months.name as month, financial_years.code as year, SUM(value) as total_' . $key . ', SUM(vat) as total_' . $key . '_vat')
                ->leftJoin('stamp_duty_configs', 'stamp_duty_configs.id', 'stamp_duty_return_items.config_id')
                ->leftJoin('stamp_duty_returns', 'stamp_duty_returns.id', 'stamp_duty_return_items.return_id')
                ->leftJoin('financial_months', 'financial_months.id', 'stamp_duty_returns.financial_month_id')
                ->leftJoin('financial_years', 'financial_years.id', 'financial_months.financial_year_id')
                ->where('stamp_duty_returns.tax_type_id', $this->taxType->id)
                ->where('stamp_duty_returns.business_location_id', $this->branch->id)
                ->whereIn('config_id', StampDutyConfig::whereIn('code', $configCodes)->select('id')->pluck('id'))
                ->groupBy(['financial_years.code', 'financial_months.name'])
                ->get();

            $this->$key = $query;
        }
    }


    private function replaceRecursiveArrays($array1, $array2)
    {
        // Check if both arrays are set and not null
        if (isset($array1) && isset($array2)) {
            // Convert arrays to arrays if they are objects
            $array1 = is_array($array1) ? $array1 : $array1->toArray();
            $array2 = is_array($array2) ? $array2 : $array2->toArray();

            // Replace recursively
            return array_replace_recursive($array1, $array2);
        } else {
            // Handle the case where either array is not set or is null
            return [];
        }
    }


    public function render()
    {
        return view('livewire.investigation.assesment-details');
    }
}
