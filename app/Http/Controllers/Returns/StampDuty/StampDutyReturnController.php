<?php

namespace App\Http\Controllers\Returns\StampDuty;

use App\Http\Controllers\Controller;
use App\Models\Returns\StampDuty\StampDutyReturn;
use App\Models\WithheldCertificateAttachment;
use Illuminate\Support\Facades\Gate;
use Illuminate\Support\Facades\Storage;

class StampDutyReturnController extends Controller
{
    public function index()
    {
        if (!Gate::allows('return-stamp-duty-return-view')) {
            abort(403);
        }

        $cardOne   = 'returns.stamp-duty.stamp-duty-card-one';
        $cardTwo   = 'returns.stamp-duty.stamp-duty-card-two';
        $tableName ='returns.stamp-duty.stamp-duty-returns-table';

        return view('returns.stamp-duty.index', compact('cardOne', 'cardTwo', 'tableName'));
    }

    public function show($returnId)
    {
        if (!Gate::allows('return-stamp-duty-return-view')) {
            abort(403);
        }
        $returnId = decrypt($returnId);
        $return   = StampDutyReturn::findOrFail($returnId);
        $return->penalties = $return->penalties->concat($return->tax_return->penalties)->sortBy('tax_amount');
        return view('returns.stamp-duty.show', compact('return'));
    }

    public function getWithheldCertificate($certificate_id){
        if (!Gate::allows('return-stamp-duty-return-view')) {
            abort(403);
        }

        $certificate = WithheldCertificateAttachment::findOrFail(decrypt($certificate_id));
        $extension = pathinfo($certificate->location, PATHINFO_EXTENSION);
        return Storage::download($certificate->location, "Withheld Certificate.$extension");
    }

    public function getWithheldCertificatesSummary($returnId){
        if (!Gate::allows('return-stamp-duty-return-view')) {
            abort(403);
        }

        $return = StampDutyReturn::findOrFail(decrypt($returnId));
        $extension = pathinfo($return->withheld_certificates_summary, PATHINFO_EXTENSION);
        return Storage::download($return->withheld_certificates_summary, "Withheld Certificates Summary.$extension");
    }
}
