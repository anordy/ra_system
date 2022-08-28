<?php

namespace App\Http\Controllers\Debt;

use App\Models\TaxType;
use App\Models\Debts\Debt;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Gate;
use App\Models\Returns\BFO\BfoReturn;
use App\Models\Returns\Vat\VatReturn;
use App\Models\Returns\Port\PortReturn;
use App\Models\Returns\MmTransferReturn;
use App\Models\Returns\EmTransactionReturn;
use App\Models\Returns\ExciseDuty\MnoReturn;
use App\Models\Returns\LumpSum\LumpSumReturn;
use App\Models\Returns\HotelReturns\HotelReturn;
use App\Models\Returns\Petroleum\PetroleumReturn;
use App\Models\Returns\StampDuty\StampDutyReturn;

class ReturnDebtController extends Controller
{

    public function index($taxType)
    {
        $taxType = decrypt($taxType);
        if ($taxType == TaxType::AIRPORT_SERVICE_SAFETY_FEE || $taxType == TaxType::SEA_SERVICE_TRANSPORT_CHARGE) {
            if (!Gate::allows("debt-management-{$taxType}-view")) {
                abort(403);
            }
            return view('debts.returns.port', compact('taxType'));
        } else {
            if (!Gate::allows("debt-management-{$taxType}-view")) {
                abort(403);
            }
            return view('debts.returns.index', compact('taxType'));
        }
    }

    public function show($id, $taxType)
    {
        $id = decrypt($id);
        if ($taxType == TaxType::HOTEL || $taxType == TaxType::RESTAURANT || $taxType == TaxType::TOUR_OPERATOR) {
            $return =  HotelReturn::find($id); 
        } else if ($taxType == TaxType::PETROLEUM) {
            $return =  PetroleumReturn::find($id);
        } else if ($taxType == TaxType::EXCISE_DUTY_BFO) {
            $return =  BfoReturn::find($id);
        }  else if ($taxType == TaxType::EXCISE_DUTY_MNO) {
            $return =  MnoReturn::find($id);
        } else if ($taxType == TaxType::STAMP_DUTY) {
            $return =  StampDutyReturn::find($id);
        } else if ($taxType == TaxType::VAT) {
            $return =  VatReturn::find($id);
        } else if ($taxType == TaxType::LUMPSUM_PAYMENT) {
            $return =  LumpSumReturn::find($id);
        } else if ($taxType == TaxType::SEA_SERVICE_TRANSPORT_CHARGE || $taxType == TaxType::AIRPORT_SERVICE_SAFETY_FEE) {
            $return =  PortReturn::find($id);
        } else if ($taxType == TaxType::ELECTRONIC_MONEY_TRANSACTION) {
            $return =  EmTransactionReturn::find($id);
        } else if ($taxType == TaxType::MOBILE_MONEY_TRANSFER) {
            $return =  MmTransferReturn::find($id);
        }  else if ($taxType == TaxType::MOBILE_MONEY_TRANSFER) {
            $return =  MmTransferReturn::find($id);
        }
        return view('debts.returns.show', compact('return', 'id'));
    }
    
}
