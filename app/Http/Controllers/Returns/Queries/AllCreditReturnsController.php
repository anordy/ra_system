<?php

namespace App\Http\Controllers\Returns\Queries;

use App\Http\Controllers\Controller;
use App\Models\TaxType;
use App\Traits\SalesTrait;
use App\Traits\ShowReturnTrait;
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
