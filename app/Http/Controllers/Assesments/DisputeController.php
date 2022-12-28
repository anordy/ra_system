<?php

namespace App\Http\Controllers\Assesments;

use App\Http\Controllers\Controller;
use App\Models\Business;
use App\Models\DisputeAttachment;
use App\Models\Disputes\Dispute;
use App\Models\TaxAssessments\TaxAssessment;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Gate;

class DisputeController extends Controller
{
    //   public function index()
    // {
    //     dd('in');
    //     if (!Gate::allows('dispute-waiver-view')) {
    //         abort(403);
    //     }

    //     return view('assesments.dispute.index');
    // }


    public function approval($waiverId)
    {
        $dispute = Dispute::findOrFail(decrypt($waiverId));
        $assesment = TaxAssessment::find($dispute->assesment_id);
        $business = Business::find($dispute->business_id);
        $files = DisputeAttachment::where('dispute_id', $dispute->id)->get();
        return view('assesments.dispute.approval', compact('dispute', 'files', 'business', 'assesment'));
    }
}
