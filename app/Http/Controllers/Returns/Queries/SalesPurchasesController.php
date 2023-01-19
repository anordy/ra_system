<?php

namespace App\Http\Controllers\Returns\Queries;

use App\Http\Controllers\Controller;
use App\Models\Returns\HotelReturns\HotelReturn;
use App\Models\Returns\HotelReturns\HotelReturnConfig;
use App\Models\Returns\HotelReturns\HotelReturnItem;
use App\Models\Returns\Queries\SalePurchase;
use App\Models\Returns\ReturnStatus;
use App\Traits\PurchasesTrait;
use App\Traits\SalesTrait;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Jantinnerezo\LivewireAlert\LivewireAlert;

class SalesPurchasesController extends Controller
{
    use PurchasesTrait, SalesTrait, LivewireAlert;

    public function index()
    {
        $constant_percentage = 10 / 100;
        $hotel_returns = array_replace_recursive($this->getHotelPurchases()->toArray(), $this->getAllHotelSales()->toArray());
        $this->saveReturns($hotel_returns, $constant_percentage);
        $vat_returns = array_replace_recursive($this->getVatPurchases()->toArray(), $this->getAllVatSales()->toArray());
        $this->saveReturns($vat_returns, $constant_percentage);
        $returns = SalePurchase::get();

        return view('returns.queries.sales-purchases.index', compact('returns'));
    }

    public function show($id)
    {
        $id = decrypt($id);
        $return = SalePurchase::query()->where('id',$id)->firstOrFail();
        return view('returns.queries.sales-purchases.show', compact('return'));
    }

    public function saveReturns($returns, $constant_percentage)
    {
        DB::beginTransaction();
        try {
            foreach ($returns as $return) {
                $return_differences = $return['total_sales'] - $return['total_purchases'];
                $total = $return['total_sales'] + $return['total_purchases'];
                $return_percentage = $return_differences / $total;
                if ($return_percentage <= $constant_percentage) {
                    SalePurchase::query()->updateOrCreate(
                        ['business_location_id' => $return['business_location_id'], 'tax_type_id' => $return['tax_type_id']],
                        [
                            'business_location_id' => $return['business_location_id'],
                            'business_id' => $return['business_id'],
                            'total_sales' => $return['total_sales'],
                            'total_purchases' => $return['total_purchases'],
                            'tax_type_id' => $return['tax_type_id'],
                            'return_id' => $return['id'],
                            'financial_month_id' => $return['financial_month_id'],
                            'financial_year_id' => $return['financial_year_id'],
                            'currency' => $return['currency'],
                            'category' => 'less than 10 percentage'
                        ]);
                }

                if ($return['total_purchases'] - $return['total_sales']  > $return['total_sales'] / 3) {
                    SalePurchase::query()->updateOrCreate(
                        ['business_location_id' => $return['business_location_id'], 'tax_type_id' => $return['tax_type_id']],
                        [
                            'business_location_id' => $return['business_location_id'],
                            'business_id' => $return['business_id'],
                            'total_sales' => $return['total_sales'],
                            'total_purchases' => $return['total_purchases'],
                            'tax_type_id' => $return['tax_type_id'],
                            'return_id' => $return['id'],
                            'financial_month_id' => $return['financial_month_id'],
                            'financial_year_id' => $return['financial_year_id'],
                            'currency' => $return['currency'],
                            'category' => 'one third of sales'
                        ]);
                }

            }
            DB::commit();

        } catch (\Throwable $exception) {
            DB::rollBack();
            Log::error($exception);
        }
    }


}
