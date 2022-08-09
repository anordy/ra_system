<?php

namespace App\Http\Livewire\Verification;

use App\Models\Returns\HotelReturns\HotelReturn;
use App\Models\Returns\HotelReturns\HotelReturnConfig;
use App\Models\Returns\HotelReturns\HotelReturnItem;
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
    public $returns;
    public $return;

    public function mount($modelName, $return)
    {
        $this->modelName = $modelName;
        $this->return = $return;

        if ($modelName === HotelReturn::class) {

            $purchaseConfigs = HotelReturnConfig::whereIn('code', ["LP", "IP"])->get()->pluck('id');

            $this->purchases = HotelReturnItem::selectRaw('financial_month_id, financial_months.name as month, financial_years.code as year, SUM(value) as total_purchases, SUM(vat) as total_purchases_vat')
                ->leftJoin('hotel_return_configs', 'hotel_return_configs.id', 'hotel_return_items.config_id')
                ->leftJoin('hotel_returns', 'hotel_returns.id', 'hotel_return_items.return_id')
                ->leftJoin('financial_months', 'financial_months.id', 'hotel_returns.financial_month_id')
                ->leftJoin('financial_years', 'financial_years.id', 'financial_months.financial_year_id')
                ->where('hotel_returns.tax_type_id', $this->return->tax_type_id)
                ->where('hotel_returns.business_location_id', $this->return->business_location_id)
                ->whereIn('config_id', $purchaseConfigs)
                ->groupBy('financial_month_id')->get();


            $salesConfigs = HotelReturnConfig::whereIn('code', ["HS", "RS", "TOS", "OS"])->get()->pluck('id');

            $this->sales = HotelReturnItem::selectRaw('financial_month_id, financial_months.name as month, financial_years.code as year, SUM(value) as total_sales, SUM(vat) as total_sales_vat')
                ->leftJoin('hotel_return_configs', 'hotel_return_configs.id', 'hotel_return_items.config_id')
                ->leftJoin('hotel_returns', 'hotel_returns.id', 'hotel_return_items.return_id')
                ->leftJoin('financial_months', 'financial_months.id', 'hotel_returns.financial_month_id')
                ->leftJoin('financial_years', 'financial_years.id', 'financial_months.financial_year_id')
                ->where('hotel_returns.tax_type_id', $this->return->tax_type_id)
                ->where('hotel_returns.business_location_id', $this->return->business_location_id)
                ->whereIn('config_id', $salesConfigs)
                ->groupBy('financial_month_id')->get();

            $returns = array_replace_recursive($this->purchases->toArray(), $this->sales->toArray());

            $calculations = array_map(function ($returns) {
                return array(
                    'financial_month' => "{$returns['month']} {$returns['year']}",
                    'total_sales' => $returns['total_sales'],
                    'total_purchases' => $returns['total_purchases'],
                    'output_vat' => $returns['total_sales_vat'],
                    'input_tax' => $returns['total_purchases_vat'],
                    'tax_paid' => ($returns['total_sales_vat']) - $returns['total_purchases_vat'],
                );
            }, $returns);

            $this->returns = $calculations;

        }
    }


    public function render()
    {
        return view('livewire.verification.assesment-details');
    }
}
