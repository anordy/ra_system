<?php

namespace App\Http\Controllers\Returns\StampDuty;

use App\Enum\CustomMessage;
use App\Http\Controllers\Controller;
use App\Models\Returns\StampDuty\StampDutyReturn;
use App\Models\WithheldCertificateAttachment;
use Illuminate\Support\Facades\Gate;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Storage;

class StampDutyReturnController extends Controller
{
    public function index()
    {
        if (!Gate::allows('return-stamp-duty-return-view')) {
            abort(403);
        }

        try {
            $cardOne = 'returns.stamp-duty.stamp-duty-card-one';
            $cardTwo = 'returns.stamp-duty.stamp-duty-card-two';
            $tableName = 'returns.stamp-duty.stamp-duty-returns-table';
            return view('returns.stamp-duty.index', compact('cardOne', 'cardTwo', 'tableName'));
        } catch (\Exception $exception) {
            Log::error('RETURNS-STAMP-DUTY-RETURN-CONTROLLER-INDEX', [$exception]);
            session()->flash('error', CustomMessage::error());
            return back();
        }
    }

    public function show($returnId)
    {
        if (!Gate::allows('return-stamp-duty-return-view')) {
            abort(403);
        }
        try {
            $returnId = decrypt($returnId);
            $return = StampDutyReturn::with(['penalties'])->findOrFail($returnId, ['id', 'filed_by_id', 'filed_by_type', 'business_id', 'tax_type_id', 'business_location_id', 'financial_month_id', 'financial_year_id', 'edited_count', 'currency', 'total_amount_due', 'total_amount_due_with_penalties', 'penalty', 'interest', 'withheld_tax', 'submitted_at', 'paid_at', 'filing_due_date', 'payment_due_date', 'status', 'claim_status', 'return_category', 'application_status', 'created_at', 'updated_at', 'vetting_status', 'withheld_certificates_summary']);
            $return->penalties = $return->penalties->concat($return->tax_return->penalties)->sortBy('tax_amount');
            return view('returns.stamp-duty.show', compact('return'));
        } catch (\Exception $exception) {
            Log::error('RETURNS-STAMP-DUTY-RETURN-CONTROLLER-SHOW', [$exception]);
            session()->flash('error', CustomMessage::error());
            return back();
        }
    }

    public function getWithheldCertificate($certificate_id)
    {
        if (!Gate::allows('return-stamp-duty-return-view')) {
            abort(403);
        }

        try {
            $certificate = WithheldCertificateAttachment::findOrFail(decrypt($certificate_id), ['location']);
            $extension = pathinfo($certificate->location, PATHINFO_EXTENSION);
            return Storage::download($certificate->location, "Withheld Certificate.$extension");
        } catch (\Exception $exception) {
            Log::error('RETURNS-STAMP-DUTY-RETURN-CONTROLLER-GET-WITHHELD-CERTIFICATE', [$exception]);
            session()->flash('error', CustomMessage::error());
            return back();
        }
    }

    public function getWithheldCertificatesSummary($returnId)
    {
        if (!Gate::allows('return-stamp-duty-return-view')) {
            abort(403);
        }

        try {
            $return = StampDutyReturn::findOrFail(decrypt($returnId), ['withheld_certificates_summary']);
            $extension = pathinfo($return->withheld_certificates_summary, PATHINFO_EXTENSION);
            return Storage::download($return->withheld_certificates_summary, "Withheld Certificates Summary.$extension");
        } catch (\Exception $exception) {
            Log::error('RETURNS-STAMP-DUTY-RETURN-CONTROLLER-GET-WITHHELD-CERTIFICATES-SUMMARY', [$exception]);
            session()->flash('error', CustomMessage::error());
            return back();
        }
    }
}
