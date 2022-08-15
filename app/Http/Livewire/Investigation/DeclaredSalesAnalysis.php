<?php

namespace App\Http\Livewire\Investigation;

use App\Models\Returns\BFO\BfoConfig;
use App\Models\Returns\BFO\BfoReturnItems;
use App\Models\Returns\EmTransactionConfig;
use App\Models\Returns\EmTransactionReturnItem;
use App\Models\Returns\ExciseDuty\MnoConfig;
use App\Models\returns\ExciseDuty\MnoReturnItem;
use App\Models\Returns\HotelReturns\HotelReturnConfig;
use App\Models\Returns\HotelReturns\HotelReturnItem;
use App\Models\Returns\MmTransferConfig;
use App\Models\Returns\MmTransferReturnItem;
use App\Models\Returns\Petroleum\PetroleumConfig;
use App\Models\Returns\Petroleum\PetroleumReturnItem;
use App\Models\Returns\Vat\VatReturnConfig;
use App\Models\Returns\Vat\VatReturnItem;
use App\Models\TaxType;
use Carbon\Carbon;
use DateTime;
use Jantinnerezo\LivewireAlert\LivewireAlert;
use Livewire\Component;

class DeclaredSalesAnalysis extends Component
{
    use LivewireAlert;

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
    public $returnTypeTable;

    public function mount($investigation)
    {

        $this->taxType = TaxType::find($investigation->tax_type_id);
        $this->branch = TaxType::find($investigation->location_id);

        $this->start_date = $this->validateDate($investigation->period_from) ? $investigation->period_from : Carbon::now();
        $this->end_date = $this->validateDate($investigation->period_to) ? $investigation->period_to : Carbon::now();

        switch ($this->taxType->code) {
            case TaxType::HOTEL:
                $this->hotel();
                break;
            case TaxType::PETROLEUM:
                $this->petroleum();
                break;
            case TaxType::VAT:
                $this->vat();
                break;
            case TaxType::EXCISE_DUTY_MNO:
                $this->returnTypeTable = TaxType::EXCISE_DUTY_MNO;
                $this->mno();
                break;
            case TaxType::EXCISE_DUTY_BFO:
                $this->returnTypeTable = TaxType::EXCISE_DUTY_BFO;
                $this->bfo();
                break;
            case TaxType::ELECTRONIC_MONEY_TRANSACTION:
                $this->returnTypeTable = TaxType::ELECTRONIC_MONEY_TRANSACTION;
                $this->emTransaction();
                break;
            case TaxType::MOBILE_MONEY_TRANSFER:
                $this->returnTypeTable = TaxType::MOBILE_MONEY_TRANSFER;
                $this->mmTransfer();
            case TaxType::STAMP_DUTY:
                $this->stampDuty();
                break;
        }
    }

    function validateDate($date, $format = 'Y-m-d')
    {
        $d = DateTime::createFromFormat($format, $date);
        return $d && $d->format($format) === $date;
    }


    protected function hotel()
    {
        $purchaseConfigs = HotelReturnConfig::whereIn('code', ["LP", "IP"])->get()->pluck('id');

        $this->purchases = HotelReturnItem::selectRaw('financial_months.name as month, financial_years.code as year, SUM(value) as total_purchases, SUM(vat) as total_purchases_vat')
            ->leftJoin('hotel_return_configs', 'hotel_return_configs.id', 'hotel_return_items.config_id')
            ->leftJoin('hotel_returns', 'hotel_returns.id', 'hotel_return_items.return_id')
            ->leftJoin('financial_months', 'financial_months.id', 'hotel_returns.financial_month_id')
            ->leftJoin('financial_years', 'financial_years.id', 'financial_months.financial_year_id')
            ->where('hotel_returns.tax_type_id', $this->taxType->id)
            ->where('hotel_returns.business_location_id', $this->branch->id)
            ->whereIn('config_id', $purchaseConfigs)
            ->groupBy(['financial_years.code', 'financial_months.name'])->get();


        $salesConfigs = HotelReturnConfig::whereIn('code', ["HS", "RS", "TOS", "OS"])->get()->pluck('id');

        $this->sales = HotelReturnItem::selectRaw('financial_months.name as month, financial_years.code as year, SUM(value) as total_sales, SUM(vat) as total_sales_vat')
            ->leftJoin('hotel_return_configs', 'hotel_return_configs.id', 'hotel_return_items.config_id')
            ->leftJoin('hotel_returns', 'hotel_returns.id', 'hotel_return_items.return_id')
            ->leftJoin('financial_months', 'financial_months.id', 'hotel_returns.financial_month_id')
            ->leftJoin('financial_years', 'financial_years.id', 'financial_months.financial_year_id')
            ->where('hotel_returns.tax_type_id', $this->taxType->id)
            ->where('hotel_returns.business_location_id', $this->branch->id)
            ->whereIn('config_id', $salesConfigs)
            ->groupBy(['financial_years.code', 'financial_months.name'])->get();

        $returns = array_replace_recursive($this->purchases->toArray(), $this->sales->toArray());

        $calculations = collect(array_map(function ($returns) {
            return array(
                'year' => $returns['year'],
                'month' => $returns['month'],
                'financial_month' => "{$returns['month']} {$returns['year']}",
                'total_sales' => $returns['total_sales'],
                'total_purchases' => $returns['total_purchases'],
                'output_vat' => $returns['total_sales_vat'],
                'input_tax' => $returns['total_purchases_vat'],
                'tax_paid' => ($returns['total_sales_vat']) - $returns['total_purchases_vat'],
            );
        }, $returns));


        $this->returns = $calculations->sortByDesc('month')->groupBy('year');
    }

    public function mno(){
        $salesConfigs = MnoConfig::where('code', '!=', 'TOTAL')->get()->pluck('id');
        $headers = MnoConfig::where('code', '!=', 'TOTAL')->get()->pluck('name');

        $yearReturnGroup = MnoReturnItem::select('mno_configs.code', 'mno_return_items.input_value', 'mno_return_items.vat', 'financial_months.name as month', 'financial_years.name as year')
            ->leftJoin('mno_configs', 'mno_configs.id', 'mno_return_items.mno_config_id')
            ->leftJoin('mno_returns', 'mno_returns.id', 'mno_return_items.mno_return_id')
            ->leftJoin('financial_months', 'financial_months.id', 'mno_returns.financial_month_id')
            ->leftJoin('financial_years', 'financial_years.id', 'financial_months.financial_year_id')
            ->whereIn('mno_config_id', $salesConfigs)
            ->get()->groupBy(['year','month']);
        
        $yearData = $this->formatDataArray($yearReturnGroup);

        $this->withoutPurchases = true;
        $this->returns = $yearData;
        $this->headersMno = $headers;
    }

    protected function petroleum()
    {
        $purchaseConfigs = PetroleumConfig::whereIn('code', ["LP", "IP"])->get()->pluck('id');

        $this->purchases = PetroleumReturnItem::selectRaw('financial_months.name as month, financial_years.code as year, SUM(value) as total_purchases, SUM(vat) as total_purchases_vat')
            ->leftJoin('hotel_return_configs', 'hotel_return_configs.id', 'hotel_return_items.config_id')
            ->leftJoin('hotel_returns', 'hotel_returns.id', 'hotel_return_items.return_id')
            ->leftJoin('financial_months', 'financial_months.id', 'hotel_returns.financial_month_id')
            ->leftJoin('financial_years', 'financial_years.id', 'financial_months.financial_year_id')
            ->where('hotel_returns.tax_type_id', $this->taxType->id)
            ->where('hotel_returns.business_location_id', $this->branch->id)
            ->whereIn('config_id', $purchaseConfigs)
            ->groupBy(['financial_years.code', 'financial_months.name'])->get();


        $salesConfigs = PetroleumConfig::whereIn('code', ["HS", "RS", "TOS", "OS"])->get()->pluck('id');

        $this->sales = PetroleumReturnItem::selectRaw('financial_months.name as month, financial_years.code as year, SUM(value) as total_sales, SUM(vat) as total_sales_vat')
            ->leftJoin('hotel_return_configs', 'hotel_return_configs.id', 'hotel_return_items.config_id')
            ->leftJoin('hotel_returns', 'hotel_returns.id', 'hotel_return_items.return_id')
            ->leftJoin('financial_months', 'financial_months.id', 'hotel_returns.financial_month_id')
            ->leftJoin('financial_years', 'financial_years.id', 'financial_months.financial_year_id')
            ->where('hotel_returns.tax_type_id', $this->taxType->id)
            ->where('hotel_returns.business_location_id', $this->branch->id)
            ->whereIn('config_id', $salesConfigs)
            ->groupBy(['financial_years.code', 'financial_months.name'])->get();

        $returns = array_replace_recursive($this->purchases->toArray(), $this->sales->toArray());

        $calculations = collect(array_map(function ($returns) {
            return array(
                'year' => $returns['year'],
                'month' => $returns['month'],
                'financial_month' => "{$returns['month']} {$returns['year']}",
                'total_sales' => $returns['total_sales'],
                'total_purchases' => $returns['total_purchases'],
                'output_vat' => $returns['total_sales_vat'],
                'input_tax' => $returns['total_purchases_vat'],
                'tax_paid' => ($returns['total_sales_vat']) - $returns['total_purchases_vat'],
            );
        }, $returns));

        dd($calculations, 'here');

        $this->returns = $calculations ?? [];
    }

    public function vat()
    {
        $purchaseConfigs = VatReturnConfig::query()->whereIn('code', ["EIP", "ELP","NCP","VDP","SLP","IP","SRI","SA","SC"])->get()->pluck('id');

        $this->purchases = VatReturnItem::query()->selectRaw('financial_months.name as month, financial_years.code as year, SUM(input_amount) as total_purchases, SUM(vat_amount) as total_purchases_vat')
            ->leftJoin('vat_return_configs', 'vat_return_configs.id', 'vat_return_items.vat_return_config_id')
            ->leftJoin('vat_returns', 'vat_returns.id', 'vat_return_items.vat_return_id')
            ->leftJoin('financial_months', 'financial_months.id', 'vat_returns.financial_month_id')
            ->leftJoin('financial_years', 'financial_years.id', 'financial_months.financial_year_id')
            ->where('vat_returns.tax_type_id', $this->taxType->id)
            ->where('vat_returns.business_location_id', $this->branch->id)
            ->whereIn('vat_return_config_id', $purchaseConfigs)
            ->groupBy(['financial_years.code', 'financial_months.name'])->get();


        $salesConfigs = VatReturnConfig::query()->whereIn('code', ["SRS", "ZRS", "ES", "SER"])->get()->pluck('id');

        $this->sales = VatReturnItem::query()->selectRaw('financial_months.name as month, financial_years.code as year, SUM(input_amount) as total_sales, SUM(vat_amount) as total_sales_vat')
            ->leftJoin('vat_return_configs', 'vat_return_configs.id', 'vat_return_items.vat_return_config_id')
            ->leftJoin('vat_returns', 'vat_returns.id', 'vat_return_items.vat_return_id')
            ->leftJoin('financial_months', 'financial_months.id', 'vat_returns.financial_month_id')
            ->leftJoin('financial_years', 'financial_years.id', 'financial_months.financial_year_id')
            ->where('vat_returns.tax_type_id', $this->taxType->id)
            ->where('vat_returns.business_location_id', $this->branch->id)
            ->whereIn('vat_return_config_id', $salesConfigs)
            ->groupBy(['financial_years.code', 'financial_months.name'])->get();

        $returns = array_replace_recursive($this->purchases->toArray(), $this->sales->toArray());

        $calculations = collect(array_map(function ($returns) {
            return array(
                'year' => $returns['year'],
                'month' => $returns['month'],
                'financial_month' => "{$returns['month']} {$returns['year']}",
                'total_sales' => $returns['total_sales'],
                'total_purchases' => $returns['total_purchases'],
                'output_vat' => $returns['total_sales_vat'],
                'input_tax' => $returns['total_purchases_vat'],
                'tax_paid' => ($returns['total_sales_vat']) - $returns['total_purchases_vat'],
            );
        }, $returns));


        $this->returns = $calculations;

    }

    protected function bfo()
    {
        $salesConfigs = BfoConfig::where('code', '!=', 'TotalFBO')->get()->pluck('id');
        $headers = BfoConfig::where('code', '!=', 'TotalFBO')->get()->pluck('name');

        $yearReturnGroup = BfoReturnItems::select('bfo_configs.code', 'bfo_return_items.value', 'bfo_return_items.vat', 'financial_months.name as month', 'financial_years.name as year')
            ->leftJoin('bfo_configs', 'bfo_configs.id', 'bfo_return_items.config_id')
            ->leftJoin('bfo_returns', 'bfo_returns.id', 'bfo_return_items.bfo_return_id')
            ->leftJoin('financial_months', 'financial_months.id', 'bfo_returns.financial_month_id')
            ->leftJoin('financial_years', 'financial_years.id', 'financial_months.financial_year_id')
            ->whereIn('config_id', $salesConfigs)
            ->get()->groupBy(['year','month']);
        

        $yearData = $this->formatDataArray($yearReturnGroup);

        $this->withoutPurchases = true;
        $this->returns = $yearData;
        $this->headersBfo = $headers;

    }

    protected function emTransaction()
    {
        $salesConfigs = EmTransactionConfig::where('code', '!=', 'TotalEMT')->get()->pluck('id');
        $headers = EmTransactionConfig::where('code', '!=', 'TotalEMT')->get()->pluck('name');

        $yearReturnGroup = EmTransactionReturnItem::select('em_transaction_configs.code', 'em_transaction_return_items.value', 'em_transaction_return_items.vat', 'seven_days_financial_months.name as month', 'financial_years.name as year')
            ->leftJoin('em_transaction_configs', 'em_transaction_configs.id', 'em_transaction_return_items.config_id')
            ->leftJoin('em_transaction_returns', 'em_transaction_returns.id', 'em_transaction_return_items.return_id')
            ->leftJoin('seven_days_financial_months', 'seven_days_financial_months.id', 'em_transaction_returns.financial_month_id')
            ->leftJoin('financial_years', 'financial_years.id', 'seven_days_financial_months.financial_year_id')
            ->whereIn('config_id', $salesConfigs)
            ->get()->groupBy(['year','month']);
        

        $yearData = $this->formatDataArray($yearReturnGroup);

        $this->withoutPurchases = true;
        $this->returns = $yearData;
        $this->headersEmTransaction = $headers;

    }

    protected function mmTranfer()
    {
        $salesConfigs = MmTransferConfig::where('code', '!=', 'TotalEMT')->get()->pluck('id');
        $headers = MmTransferConfig::where('code', '!=', 'TotalEMT')->get()->pluck('name');

        $yearReturnGroup = MmTransferReturnItem::select('mm_transfer_configs.code', 'mm_transfer_return_items.value', 'mm_transfer_return_items.vat', 'seven_days_financial_months.name as month', 'financial_years.name as year')
            ->leftJoin('mm_transfer_configs', 'mm_transfer_configs.id', 'mm_transfer_return_items.config_id')
            ->leftJoin('mm_transfer_returns', 'mm_transfer_returns.id', 'mm_transfer_return_items.return_id')
            ->leftJoin('seven_days_financial_months', 'seven_days_financial_months.id', 'mm_transfer_returns.financial_month_id')
            ->leftJoin('financial_years', 'financial_years.id', 'seven_days_financial_months.financial_year_id')
            ->whereIn('config_id', $salesConfigs)
            ->get()->groupBy(['year','month']);

        $yearData = $this->formatDataArray($yearReturnGroup);
        

        $this->withoutPurchases = true;
        $this->returns = $yearData;
        $this->headersMmTransfer = $headers;

    }

    protected function formatDataArray($yearReturnGroup){
        $yearData = [];


        foreach($yearReturnGroup as $keyYear => $monthreturnGroup){
            $monthData = [];
            foreach($monthreturnGroup as $keyMonth => $returnItems){
                $itemValue = [
                    'month' => $keyMonth,
                ];
                $totalVat = 0;
                $totalValue = 0;
                foreach ($returnItems as $keyItem => $item) {
                    $itemValue[$item['code']]=$item['value'];
                    $totalValue += $item['value'];
                    $totalVat += $item['vat'];
                }
                $itemValue['totalValue'] = $totalValue;
                $itemValue['totalVat'] = $totalVat;
                $monthData[] =  $itemValue;
            }
            $yearData[$keyYear] = $monthData;
        }

        return $yearData;
    }


    public function render()
    {
        return view('livewire.investigation.assesment-details');
    }
}
