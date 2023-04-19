<?php

namespace App\Http\Controllers\Returns\StampDuty;

use App\Http\Controllers\Controller;
use App\Models\Returns\StampDuty\StampDutyReturn;
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

    public function getWithheldCertificate($returnId){
        if (!Gate::allows('return-stamp-duty-return-view')) {
            abort(403);
        }

        $return = StampDutyReturn::findOrFail(decrypt($returnId));
        return Storage::response($return->withheld_certificate);
    }
}
