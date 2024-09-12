<?php

namespace App\Http\Controllers\Returns;

use App\Enum\CustomMessage;
use App\Http\Controllers\Controller;
use App\Models\Returns\Port\PortReturn;
use App\Models\Returns\TaxReturn;
use App\Models\TaxType;
use Barryvdh\DomPDF\Facade\Pdf;
use Illuminate\Support\Facades\Gate;
use Illuminate\Support\Facades\Log;

class PrintController extends Controller
{
    public function print($taxReturnId){
        if (!Gate::allows('print-return')) {
            abort(403);
        }

        try {
            $taxReturn = TaxReturn::findOrFail(decrypt($taxReturnId), ['id', 'business_id', 'location_id', 'return_id', 'return_type', 'filed_by_id', 'filed_by_type', 'tax_type_id', 'financial_month_id', 'currency', 'principal', 'interest', 'penalty', 'infrastructure', 'airport_safety_fee', 'airport_service_charge', 'seaport_service_charge', 'seaport_transport_charge', 'infrastructure_znz_znz', 'infrastructure_znz_tz', 'rdf_fee', 'road_license_fee', 'withheld_tax', 'credit_brought_forward', 'total_amount', 'outstanding_amount', 'lumpsum_quarter', 'has_claim', 'is_nill', 'filing_method', 'return_status', 'payment_status', 'return_category', 'application_step', 'application_status', 'payment_method', 'payment_due_date', 'filing_due_date', 'curr_payment_due_date', 'curr_filing_due_date', 'sub_vat_id', 'vetting_status', 'marking', 'parent']);
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
                case TaxType::SEAPORT_SERVICE_TRANSPORT_CHARGE:
                    $return_ = TaxReturn::select(['id', 'business_id', 'location_id', 'return_id', 'return_type', 'filed_by_id', 'filed_by_type', 'tax_type_id', 'financial_month_id', 'currency', 'principal', 'interest', 'penalty', 'infrastructure', 'airport_safety_fee', 'airport_service_charge', 'seaport_service_charge', 'seaport_transport_charge', 'infrastructure_znz_znz', 'infrastructure_znz_tz', 'rdf_fee', 'road_license_fee', 'withheld_tax', 'credit_brought_forward', 'total_amount', 'outstanding_amount', 'lumpsum_quarter', 'has_claim', 'is_nill', 'filing_method', 'return_status', 'payment_status', 'return_category', 'application_step', 'application_status', 'payment_method', 'payment_due_date', 'filing_due_date', 'curr_payment_due_date', 'curr_filing_due_date', 'sub_vat_id', 'vetting_status', 'marking', 'parent'])
                        ->where('parent', $taxReturn->id)->first() ?? null;
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
                    session()->flash('error', CustomMessage::error());
                    return redirect()->back();
            }

            $pdf = PDF::loadView('print.returns.index', ['return' => $taxReturn->return, 'returnView' => $returnView]);
            $pdf->setPaper('a4', 'portrait');
            $pdf->setOption(['dpi' => 150, 'defaultFont' => 'sans-serif']);
            return $pdf->stream();
        } catch (\Exception $exception) {
            Log::error('PRINT-CONTROLLER-PRINT', [$exception]);
            session()->flash('error', CustomMessage::error());
            return back();
        }

    }
}
