<?php

namespace App\Http\Controllers\Assesments;

use App\Http\Controllers\Controller;
use App\Models\Business;
use App\Models\DisputeAttachment;
use App\Models\Disputes\Dispute;
use App\Models\TaxAssessments\TaxAssessment;
use Exception;
use Illuminate\Support\Facades\Gate;
use Illuminate\Support\Facades\Log;
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
        if (!Gate::allows('tax-auditing-approval-view')) {
            abort(403);
        }

        try {
            $dispute = Dispute::findOrFail(decrypt($waiverId));
            $assesment = TaxAssessment::findOrFail($dispute->assesment_id);
            $business = Business::findOrFail($dispute->business_id);
            $files = DisputeAttachment::where('dispute_id', $dispute->id)->get();
            return view('assesments.waiver.approval', compact('dispute', 'files', 'business', 'assesment'));
        } catch (Exception $e) {
            report($e);
            Log::error('Error: ' . $e->getMessage(), [
                'file' => $e->getFile(),
                'line' => $e->getLine(),
                'trace' => $e->getTraceAsString(),
            ]);
            session()->flash('warning', 'The selected waiver was not found. Please contact your administrator');
            return back();
        }
    }

    public function view($waiverId)
    {
        if (!Gate::allows('tax-auditing-approval-view')) {
            abort(403);
        }

        try {
            $dispute = Dispute::findOrFail(decrypt($waiverId));
            $assesment = TaxAssessment::findOrFail($dispute->assesment_id);
            $business = Business::findOrFail($dispute->business_id);
            $files = DisputeAttachment::where('dispute_id', $dispute->id)->get();
            return view('assesments.waiver.view', compact('dispute', 'files', 'business', 'assesment'));
        } catch (Exception $e) {
            report($e);
            Log::error('Error: ' . $e->getMessage(), [
                'file' => $e->getFile(),
                'line' => $e->getLine(),
                'trace' => $e->getTraceAsString(),
            ]);
            session()->flash('warning', 'The selected waiver was not found. Please contact your administrator');
            return back();
        }
    }
    public function files($path)
    {
        if ($path) {
            try {
                return Storage::disk('local')->response(decrypt($path));
            } catch (Exception $e) {
                report($e);
                abort(404);
            }
        }
        return abort(404);
    }
}
