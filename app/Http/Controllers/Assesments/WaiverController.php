<?php

namespace App\Http\Controllers\Assesments;

use App\Http\Controllers\Controller;
use App\Models\Business;
use App\Models\DisputeAttachment;
use App\Models\Disputes\Dispute;
use App\Models\TaxAssessments\TaxAssessment;
use Exception;
use Illuminate\Support\Facades\Gate;
use Illuminate\Support\Facades\Storage;

class WaiverController extends Controller
{
    public function index()
    {
        if (!Gate::allows('dispute-waiver-view')) {
            abort(403);
        }
        return view('assesments.waiver.index');
    }



    public function approval($waiverId)
    {
        $dispute = Dispute::findOrFail(decrypt($waiverId));
        $assesment = TaxAssessment::find($dispute->assesment_id);
        $business = Business::find($dispute->business_id);
        $files = DisputeAttachment::where('dispute_id', $dispute->id)->get();
        return view('assesments.waiver.approval', compact('dispute', 'files', 'business', 'assesment'));
    }

      public function view($waiverId)
    {
        $dispute = Dispute::findOrFail(decrypt($waiverId));
        $assesment = TaxAssessment::find($dispute->assesment_id);
        $business = Business::find($dispute->business_id);
        $files = DisputeAttachment::where('dispute_id', $dispute->id)->get();
        return view('assesments.waiver.view', compact('dispute', 'files', 'business', 'assesment'));
    }
    public function files($path)
    {
        if ($path) {
            try {
                return Storage::disk('local-admin')->response(decrypt($path));
            } catch (Exception $e) {
                report($e);
                abort(404);
            }
        }
        return abort(404);
    }
}
