<?php

namespace App\Http\Controllers\Returns\Vat;

use App\Enum\CustomMessage;
use App\Http\Controllers\Controller;
use App\Models\Returns\Vat\VatReturn;
use App\Models\Returns\Vat\VatWithheldAttachment;
use App\Traits\PaymentsTrait;
use Illuminate\Support\Facades\Gate;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Storage;

class VatReturnController extends Controller
{
    use  PaymentsTrait;

    public function index()
    {
        if (!Gate::allows('return-vat-return-view')) {
            abort(403);
        }

        try {
            $cardOne    = 'returns.vat.vat-card-one';
            $cardTwo    = 'returns.vat.vat-card-two';
            $tableName  = 'returns.vat.vat-return-table';

            return view('returns.vat_returns.index', compact('cardOne', 'cardTwo', 'tableName'));
        } catch (\Exception $exception) {
            Log::error('RETURNS-VAT-RETURN-CONTROLLER-INDEX', [$exception]);
            session()->flash('error', CustomMessage::error());
            return redirect()->back();
        }

    }

    public function show($id)
    {
        if (!Gate::allows('return-vat-return-view')) {
            abort(403);
        }

        try {
            $return         = VatReturn::with(['penalties'])->findOrFail(decrypt($id), ['id', 'business_id', 'business_location_id', 'financial_month_id', 'financial_year_id', 'tax_type_id', 'business_type', 'currency', 'total_output_tax', 'total_input_tax', 'total_vat_payable', 'vat_withheld', 'infrastructure_tax', 'credit_brought_forward', 'total_amount_due', 'penalty', 'interest', 'total_amount_due_with_penalties', 'has_exemption', 'editing_count', 'method_used', 'filed_by_id', 'filed_by_type', 'filing_due_date', 'payment_due_date', 'submitted_at', 'paid_at', 'claim_status', 'status', 'application_status', 'return_category', 'created_at', 'updated_at', 'sub_vat_id', 'vetting_status']);
            $return->penalties = $return->penalties->concat($return->tax_return->penalties)->sortBy('tax_amount');
            return view('returns.vat_returns.show', compact('return', 'id'));
        } catch (\Exception $exception) {
            Log::error('RETURNS-VAT-RETURN-CONTROLLER-SHOW', [$exception]);
            session()->flash('error', CustomMessage::error());
            return redirect()->back();
        }

    }


    public function getFile($id, $type){
        if (!Gate::allows('return-vat-return-view')) {
            abort(403);
        }

        try {
            $withheld = VatWithheldAttachment::select('withheld_file')->where('id',decrypt($id))->first();

            if ($type == 'withheld') {
                return Storage::disk('local')->response($withheld->withheld_file);
            }
            return abort(404);
        } catch (\Exception $exception) {
            Log::error('RETURNS-VAT-RETURN-CONTROLLER-GET-FILE', [$exception]);
            session()->flash('error', CustomMessage::error());
            return redirect()->back();
        }

    }
}
