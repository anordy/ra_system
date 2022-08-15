<?php

namespace App\Http\Livewire\Audit;

use App\Models\BusinessLocation;
use App\Models\Returns\HotelReturns\HotelReturnConfig;
use App\Models\Returns\HotelReturns\HotelReturnItem;
use App\Models\Returns\Petroleum\PetroleumConfig;
use App\Models\Returns\Petroleum\PetroleumReturnItem;
use App\Models\Returns\Port\PortConfig;
use App\Models\Returns\Port\PortReturnItem;
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

    public function mount($audit)
    {

        $this->taxType = TaxType::find($audit->tax_type_id);
        $this->branch = BusinessLocation::find($audit->location_id);

        $this->start_date = $this->validateDate($audit->period_from) ? $audit->period_from : Carbon::now();
        $this->end_date = $this->validateDate($audit->period_to) ? $audit->period_to : Carbon::now();

        switch ($this->taxType->code) {
            case TaxType::HOTEL:
                $this->hotel();
                break;
            case TaxType::PETROLEUM:
                $this->petroleum();
                break;
            case TaxType::AIRPORT_SERVICE_SAFETY_FEE:
            case TaxType::SEA_SERVICE_TRANSPORT_CHARGE:
                $this->airportAndSea();
                break;
            case TaxType::VAT:
                $this->vat();
                break;
            case TaxType::EXCISE_DUTY_MNO:
                $this->mno();
                break;
        }

    }

    public function validateDate($date, $format = 'Y-m-d')
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
        $purchaseConfigs = VatReturnConfig::query()->whereIn('code', ["EIP", "ELP", "NCP", "VDP", "SLP", "IP", "SRI", "SA", "SC"])->get()->pluck('id');

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

    protected function airportAndSea()
    {
        $purchaseConfigs = PortConfig::whereIn('code', [""])->get()->pluck('id');

        $headers = PortConfig::whereIn('code', ["NFAT", "NFAT", "NFSF", "NLSF", "NFSP", "NLTM", "NLZNZ", "NSUS", "NSTZ"])->get()->pluck('name');

        // dd($headers);

        $this->purchases = PortReturnItem::selectRaw('financial_months.name as month, financial_years.code as year, SUM(value) as total_purchases, SUM(vat) as total_purchases_vat')
            ->leftJoin('port_configs', 'port_configs.id', 'port_return_items.config_id')
            ->leftJoin('port_returns', 'port_returns.id', 'port_return_items.return_id')
            ->leftJoin('financial_months', 'financial_months.id', 'port_returns.financial_month_id')
            ->leftJoin('financial_years', 'financial_years.id', 'financial_months.financial_year_id')
            ->where('port_returns.tax_type_id', $this->taxType->id)
            ->where('port_returns.business_location_id', $this->branch->id)
            ->whereIn('config_id', $headers)
            ->groupBy(['financial_years.code', 'financial_months.name'])->get();

       

        $this->purchases = PortReturnItem::selectRaw('financial_months.name as month, financial_years.code as year, SUM(value) as total_purchases, SUM(vat) as total_purchases_vat')
            ->leftJoin('port_configs', 'port_configs.id', 'port_return_items.config_id')
            ->leftJoin('port_returns', 'port_returns.id', 'port_return_items.return_id')
            ->leftJoin('financial_months', 'financial_months.id', 'port_returns.financial_month_id')
            ->leftJoin('financial_years', 'financial_years.id', 'financial_months.financial_year_id')
            ->where('port_returns.tax_type_id', $this->taxType->id)
            ->where('port_returns.business_location_id', $this->branch->id)
            ->whereIn('config_id', $purchaseConfigs)
            ->groupBy(['financial_years.code', 'financial_months.name'])->get();

        $salesConfigs = PortConfig::whereIn('code', ["NFAT", "NFAT", "NFSF", "NLSF", "NFSP", "NLTM", "NLZNZ", "NSUS", "NSTZ"])->get()->pluck('id');
        // dd($salesConfigs);

        $this->sales = PortReturnItem::selectRaw('financial_months.name as month, financial_years.code as year, sum(value) as total_sales, SUM(vat) as total_sales_vat')
            ->leftJoin('port_configs', 'port_configs.id', 'port_return_items.config_id')
            ->leftJoin('port_returns', 'port_returns.id', 'port_return_items.return_id')
            ->leftJoin('financial_months', 'financial_months.id', 'port_returns.financial_month_id')
            ->leftJoin('financial_years', 'financial_years.id', 'financial_months.financial_year_id')
            ->where('port_returns.tax_type_id', $this->taxType->id)
            ->where('port_returns.business_location_id', $this->branch->id)
            ->whereIn('config_id', $salesConfigs)
            ->groupBy(['financial_years.code', 'financial_months.name'])->get();


        $returns = array_replace_recursive($this->purchases->toArray(), $this->sales->toArray());

        $calculations = collect(array_map(function ($returns) {
            return array(
                'year' => $returns['year'],
                'month' => $returns['month'],
                'financial_month' => "{$returns['month']} {$returns['year']}",
                'total_sales' => $returns['total_sales'],
                'total_purchases' => 0,
                'output_vat' => $returns['total_sales_vat'],
                'input_tax' => 0,
                'tax_paid' => ($returns['total_sales_vat']) - 0,
            );
        }, $returns));

        $this->returns = $calculations->sortByDesc('month')->groupBy('year');

    }

    public function render()
    {
        return view('livewire.investigation.assesment-details');
    }
}
