<?php

namespace App\Http\Controllers\Returns\Queries;

use App\Http\Controllers\Controller;
use App\Models\Business;
use App\Models\BusinessLocation;
use App\Models\BusinessTaxType;
use App\Models\FinancialMonth;
use App\Models\FinancialYear;
use App\Models\Returns\BFO\BfoReturn;
use App\Models\Returns\EmTransactionReturn;
use App\Models\Returns\ExciseDuty\MnoReturn;
use App\Models\Returns\HotelReturns\HotelReturn;
use App\Models\Returns\LumpSum\LumpSumReturn;
use App\Models\Returns\MmTransferReturn;
use App\Models\Returns\Petroleum\PetroleumReturn;
use App\Models\Returns\Port\PortReturn;
use App\Models\Returns\Queries\NonFiler;
use App\Models\Returns\StampDuty\StampDutyReturn;
use App\Models\Returns\Vat\VatReturn;
use App\Models\TaxType;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

class NonFilersController extends Controller
{

    public function index()
    {
        $id = $this->getTaxType(TaxType::VAT)->id;
        $model = VatReturn::class;
        $vat = $this->return($model, $id) ?? [];
        if (count($vat) > 0) {
            $result = $this->saveNonFiler($vat, $model, $id);
        }

        $id = $this->getTaxType(TaxType::HOTEL)->id;
        $model = HotelReturn::class;
        $hotel = $this->return($model, $id) ?? [];
        if (count($hotel) > 0) {
            $result = $this->saveNonFiler($hotel, $model, $id);
        }

        $id = $this->getTaxType(TaxType::STAMP_DUTY)->id;
        $model = StampDutyReturn::class;
        $stamp = $this->return($model, $id) ?? [];
        if (count($stamp) > 0) {
            $result = $this->saveNonFiler($stamp, $model, $id);
        }

        $id = $this->getTaxType(TaxType::LUMPSUM_PAYMENT)->id;
        $model = LumpSumReturn::class;
        $lump = $this->return($model, $id) ?? [];
        if (count($lump) > 0) {
            $result = $this->saveNonFiler($lump, $model, $id);
        }

        $id = $this->getTaxType(TaxType::EXCISE_DUTY_MNO)->id;
        $model = MnoReturn::class;
        $mno = $this->return($model, $id) ?? [];
        if (count($mno) > 0) {
            $result = $this->saveNonFiler($mno, $model, $id);
        }

        $id = $this->getTaxType(TaxType::ELECTRONIC_MONEY_TRANSACTION)->id;
        $model = EmTransactionReturn::class;
        $em = $this->return($model, $id) ?? [];
        if (count($em) > 0) {
            $result = $this->saveNonFiler($em, $model, $id);
        }

        $id = $this->getTaxType(TaxType::MOBILE_MONEY_TRANSFER)->id;
        $model = MmTransferReturn::class;
        $mm = $this->return($model, $id) ?? [];
        if (count($mm) > 0) {
            $result = $this->saveNonFiler($mm, $model, $id);
        }

        $id = $this->getTaxType(TaxType::PETROLEUM)->id;
        $model = PetroleumReturn::class;
        $petroleum = $this->return($model, $id) ?? [];
        if (count($petroleum) > 0) {
            $result = $this->saveNonFiler($petroleum, $model, $id);
        }

        $id = $this->getTaxType(TaxType::EXCISE_DUTY_BFO)->id;
        $model = BfoReturn::class;
        $bfo = $this->return($model, $id) ?? [];
        if (count($bfo) > 0) {
            $result = $this->saveNonFiler($bfo, $model, $id);
        }

        $id = $this->getTaxType(TaxType::AIRPORT_SERVICE_SAFETY_FEE)->id;
        $model = PortReturn::class;
        $airport = $this->return($model, $id) ?? [];
        if (count($airport) > 0) {
            $result = $this->saveNonFiler($airport, $model, $id);
        }

        $id = $this->getTaxType(TaxType::SEA_SERVICE_TRANSPORT_CHARGE)->id;
        $model = PortReturn::class;
        $seaport = $this->return($model, $id) ?? [];
        if (count($seaport) > 0) {
            $result = $this->saveNonFiler($seaport, $model, $id);
        }

        $non_filers = NonFiler::all();
        return view('returns.queries.non-filers.index', compact('non_filers'));
    }

    public function show($id)
    {
        $id = decrypt($id);
        $non_filer = NonFiler::query()->where('id',$id)->first();
        return view('returns.queries.non-filers.show', compact('non_filer'));
    }

    public function getTaxType($code)
    {
        $query = TaxType::query()->where('code', $code)->first();
        return $query;
    }

    public function return($modelName, $taxTypeId)
    {
        $businessTaxType = BusinessTaxType::query()->where('tax_type_id', $taxTypeId)->get();
        $business_id = [];
        foreach ($businessTaxType as $item) {
            $business_id[] = $item->business_id;
        }
        $business_location = BusinessLocation::query()->whereIn('business_id', $business_id)->get();

        $business_lo = [];
        foreach ($business_location as $value) {
            $business_lo[] = $value->id;
            $date_of_commencing[] = $value->date_of_commencing;
        }
        $locationsBiz = VatReturn::query()->whereIn('business_location_id', $business_lo)
            ->groupBy('business_location_id')->get();
        $rows = count($locationsBiz);
        $returnTableName = (new $modelName())->getTable();
        $returns = $modelName::selectRaw('financial_month_id as month, created_at, financial_year_id, id, business_location_id, filed_by_id')
            ->whereIn('business_location_id', $business_lo)
            ->orderByDesc('id')
            ->groupBy(['business_location_id','financial_month_id'])
            ->limit($rows)
            ->get();

        if (count($returns) > 0) {
            foreach ($returns as $return) {
                $month = FinancialMonth::query()->where('id', $return->month)->first();
                $non_filers = BusinessLocation::query()
                    ->select('business_locations.business_id', 'business_locations.id as location_id', DB::raw('TIMESTAMPDIFF(MONTH,"' . $month->due_date . '", CURDATE()) as months'))
                    ->whereIn('business_locations.business_id', $business_id)
                    ->havingRaw('TIMESTAMPDIFF(MONTH,"' . $month->due_date . '", CURDATE()) >= ?', [3])
                    ->get()->toArray();
            }
            return $non_filers;
        } else {
            foreach ($business_location as $item) {
                $month = $item->date_of_commencing;
                $non_filers = BusinessLocation::query()
                    ->select('business_locations.business_id', 'business_locations.id as location_id', DB::raw('TIMESTAMPDIFF(MONTH,"' . $month . '", CURDATE()) as months'))
                    ->whereIn('business_locations.business_id', $business_id)
                    ->havingRaw('TIMESTAMPDIFF(MONTH,"' . $month . '", CURDATE()) >= ?', [3])
                    ->get()->toArray();
                return $non_filers;
            }
        }
    }

    public function saveNonFiler($result, $model, $tax_type_id)
    {
        DB::beginTransaction();
        try {
            foreach ($result as $item) {
                $business_id = $item['business_id'];
                $location_id = $item['location_id'];
                $non = NonFiler::query()->updateOrCreate(
                    ['business_location_id' => $location_id, 'tax_type_id' => $tax_type_id],
                    [
                        'business_location_id' => $location_id,
                        'business_id' => $business_id,
                        'tax_type_id' => $tax_type_id,
                        'return_type' => $model,
                        'logged_date' => Carbon::now()->toDateTimeString(),
                    ]
                );
            }
            DB::commit();
            return true;
        } catch (\Throwable $exception) {
            DB::rollBack();
            Log::error($exception);
        }

    }


}
