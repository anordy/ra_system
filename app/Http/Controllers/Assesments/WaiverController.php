<?php

namespace App\Http\Controllers\Assesments;

use App\Http\Controllers\Controller;
use App\Models\Business;
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

    // public function show($waiverId)
    // {

    //     $waiver = Waiver::findOrfail(decrypt($waiverId));
    //     $business = Business::find($waiver->business_id);
    //     $files = WaiverAttachment::where('waiver_id', $waiver->id)->get();

    //     return view('assesments.waiver.show', compact('waiver', 'business'));
    // }

    public function edit()
    {
        return view('assesments.waiver.edit');
    }

    public function approval($waiverId)
    {
        $waiver = Waiver::findOrFail(decrypt($waiverId));
        $business = Business::find($waiver->business_id);
        $files = WaiverAttachment::where('waiver_id', $waiver->id)->get();

        return view('assesments.waiver.approval', compact('waiver', 'files', 'business'));
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
