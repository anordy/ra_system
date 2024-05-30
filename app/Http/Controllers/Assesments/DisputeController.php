<?php

namespace App\Http\Controllers\Assesments;

use App\Http\Controllers\Controller;
use App\Models\Business;
use App\Models\DisputeAttachment;
use App\Models\Disputes\Dispute;
use App\Models\TaxAssessments\TaxAssessment;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Gate;
use Illuminate\Support\Facades\Log;

class DisputeController extends Controller
{
    public function approval($waiverId)
    {

        try {
            $dispute = Dispute::findOrFail(decrypt($waiverId));
            $assesment = TaxAssessment::findOrFail($dispute->assesment_id);;
            $business = Business::findOrFail($dispute->business_id);
            $files = DisputeAttachment::where('dispute_id', $dispute->id)->get();
            return view('assesments.dispute.approval', compact('dispute', 'files', 'business', 'assesment'));
        } catch (\Exception $e) {
            report($e);
            Log::error('Error: ' . $e->getMessage(), [
                'file' => $e->getFile(),
                'line' => $e->getLine(),
                'trace' => $e->getTraceAsString(),
            ]);
            session()->flash('warning', 'The selected audit was not found. Please contact your administrator');
            return back();
        }
    }
}
