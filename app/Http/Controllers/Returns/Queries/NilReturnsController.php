<?php

namespace App\Http\Controllers\Returns\Queries;

use App\Http\Controllers\Controller;
use App\Models\BusinessLocation;
use App\Models\BusinessTaxType;
use App\Models\Returns\Vat\VatReturn;

class NilReturnsController extends Controller
{

    public function index()
    {
        $businessTaxType = BusinessTaxType::query()->where('tax_type_id', 1)->get();
        foreach ($businessTaxType as $item) {
            $business_id[] = $item->business_id;
        }
        $business_location = BusinessLocation::query()->whereIn('business_id', $business_id)->get();
        foreach ($business_location as $value) {
            $business_lo[] = $value->id;
            $date_of_commencing[] = $value->date_of_commencing;
        }
        $locationsBiz = VatReturn::query()->whereIn('business_location_id', $business_lo)
            ->groupBy('business_location_id')->get();
        $rows = count($locationsBiz);

        $vats = VatReturn::query()->select('*')
            ->whereIn('business_location_id', $business_lo)
            ->where('total_amount_due', 0)
            ->orderBy('id')
            ->groupBy('business_location_id')
            ->havingRaw('COUNT(*) >= ?', [2])
            ->limit($rows)
            ->get();
        
        $locations = [];
        foreach ($vats as $vat) {
            $locations[] = $vat->business_location_id;
        }
        $check = VatReturn::query()->whereIn('business_location_id', $locations);
        return view('returns.queries.nil-returns.index');
    }

    public function show($id)
    {
        return view('returns.queries.nil-returns.show');
    }
}
