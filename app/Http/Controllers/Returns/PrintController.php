<?php

namespace App\Http\Controllers\Returns;

use App\Http\Controllers\Controller;
use App\Models\Returns\Port\PortReturn;
use App\Models\Returns\StampDuty\StampDutyReturn;
use App\Models\Returns\TaxReturn;
use App\Models\TaxType;
use Barryvdh\DomPDF\Facade\Pdf;
use Illuminate\Http\Request;

class PrintController extends Controller
{
    public function print($taxReturnId){
        $taxReturn = TaxReturn::findOrFail(decrypt($taxReturnId));
        switch ($taxReturn->taxType->code){
            case TaxType::STAMP_DUTY:
                $returnView = 'print.returns.includes.stamp-duty';
                break;
            case TaxType::ELECTRONIC_MONEY_TRANSACTION:
                $returnView = 'print.returns.includes.em-transaction';
                break;
            case TaxType::MOBILE_MONEY_TRANSFER:
                $returnView = 'print.returns.includes.mm-transfer';
                break;
            case TaxType::EXCISE_DUTY_MNO:
                $returnView = 'print.returns.includes.mno';
                break;
            case TaxType::EXCISE_DUTY_BFO:
                $returnView = 'print.returns.includes.bfo';
                break;
            case TaxType::VAT:
                $returnView = 'print.returns.includes.vat';
                break;
            case TaxType::HOTEL:
            case TaxType::RESTAURANT:
            case TaxType::TOUR_OPERATOR:
            case TaxType::AIRBNB:
                $returnView = 'print.returns.includes.hotel';
                break;
            case TaxType::PETROLEUM:
                $returnView = 'print.returns.includes.petroleum';
                break;
            case TaxType::AIRPORT_SERVICE_SAFETY_FEE:
            case TaxType::SEA_SERVICE_TRANSPORT_CHARGE:
                $return_ = PortReturn::where('parent', $taxReturn->return->id)->first();
                $pdf = PDF::loadView('print.returns.port', ['return' => $taxReturn->return, 'return_' => $return_]);
                $pdf->setPaper('a4', 'portrait');
                $pdf->setOption(['dpi' => 150, 'defaultFont' => 'sans-serif']);
                return $pdf->stream();
            case TaxType::LUMPSUM_PAYMENT:
                $pdf = PDF::loadView('print.returns.lumpsum', ['return' => $taxReturn->return]);
                $pdf->setPaper('a4', 'portrait');
                $pdf->setOption(['dpi' => 150, 'defaultFont' => 'sans-serif']);
                return $pdf->stream();
            default:
                return;
        }

        $pdf = PDF::loadView('print.returns.index', ['return' => $taxReturn->return, 'returnView' => $returnView]);
        $pdf->setPaper('a4', 'portrait');
        $pdf->setOption(['dpi' => 150, 'defaultFont' => 'sans-serif']);
        return $pdf->stream();
    }
}
