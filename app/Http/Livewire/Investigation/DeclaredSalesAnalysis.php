<?php

namespace App\Http\Livewire\Investigation;

use App\Models\Returns\HotelReturns\HotelReturnConfig;
use App\Models\Returns\HotelReturns\HotelReturnItem;
use App\Models\Returns\Petroleum\PetroleumConfig;
use App\Models\Returns\Petroleum\PetroleumReturnItem;
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


    public function render()
    {
        return view('livewire.investigation.assesment-details');
    }
}
