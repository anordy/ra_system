<?php

namespace App\Http\Controllers\Assesments;

use App\Http\Controllers\Controller;
use App\Models\Business;
use App\Models\Disputes\Dispute;
use App\Models\DisputeAttachment;
use App\Models\TaxAssessments\TaxAssessment;
use App\Models\Verification\TaxVerificationAssessment;
use App\Models\Waiver;
use App\Models\WaiverAttachment;
use Exception;
use Illuminate\Support\Facades\Storage;

class WaiverController extends Controller
{
    public function index()
    {
        return view('assesments.waiver.index');
    }

    public function edit()
    {
        return view('assesments.waiver.edit');
    }

    public function approval($waiverId)
    {
        $dispute = Dispute::findOrFail(decrypt($waiverId));
        $assesment = TaxAssessment::find($dispute->assesment_id);
        $business = Business::find($dispute->business_id);
        $files = DisputeAttachment::where('dispute_id', $dispute->id)->get();
        return view('assesments.waiver.approval', compact('dispute', 'files', 'business','assesment'));
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
