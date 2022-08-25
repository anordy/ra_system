<?php

namespace App\Http\Controllers\Returns\Queries;

use App\Http\Controllers\Controller;
use App\Models\BusinessTaxType;
use App\Models\Returns\HotelReturns\HotelReturn;
use App\Models\Returns\HotelReturns\HotelReturnConfig;
use App\Models\Returns\HotelReturns\HotelReturnItem;
use App\Models\Returns\LumpSum\LumpSumReturn;
use App\Models\Returns\ReturnStatus;
use App\Models\Returns\StampDuty\StampDutyConfig;
use App\Models\Returns\StampDuty\StampDutyReturn;
use App\Models\Returns\Vat\VatReturn;
use App\Models\Returns\Vat\VatReturnConfig;
use App\Models\TaxType;
use App\Traits\Queries\SalesTrait;
use App\Traits\Queries\ShowReturnTrait;
use Illuminate\Http\Request;

class AllCreditReturnsController extends Controller
{
    use SalesTrait, ShowReturnTrait;

    public function index()
    {
        $hotel_returns = $this->getAllHotelSales()['return'];
        $vat_returns = $this->getAllVatSales()['return'];
        $stamp_duty_returns = $this->getAllStampDutySales()['return'];
        $vat_tax_type_id = $this->getAllVatSales()['vat_tax_type_id'];
        $stamp_tax_type_id = $this->getAllStampDutySales()['stamp_duty_tax_type_id'];
        $hotel_tax_type_id = $this->getAllHotelSales()['hotel_tax_type_id'];
        return view('returns.queries.all-credit-returns.index',
            compact('hotel_returns', 'vat_returns', 'stamp_duty_returns','vat_tax_type_id',
            'stamp_tax_type_id','hotel_tax_type_id'));
    }

    public function show($id, $tax_type_id, $sales)
    {
        $return_id = decrypt($id);
        $sales = decrypt($sales);
        $tax_type_id = decrypt($tax_type_id);
        $tax_type = TaxType::query()->findOrFail($tax_type_id);
        $tax_type_code = $tax_type->code;

        $return = $this->getReturn($tax_type_code, $return_id);

        $currency = $this->getCurrency($return->business_id, $return->tax_type_id);
        return view('returns.queries.all-credit-returns.show', compact('return', 'return_id', 'sales','currency'));
    }





}
