<?php

namespace App\Http\Controllers\Assesments;

use App\Http\Controllers\Controller;
use App\Models\Business;
use App\Models\Waiver;

class WaiverController extends Controller
{
    public function index()
    {
        return view('assesments.waiver.index');
    }

    public function show($waiverId)
    {

        $waiver = Waiver::findOrfail(decrypt($waiverId));
        $business = Business::find($waiver->business_id);
        return view('assesments.waiver.show', compact('waiver', 'business'));
    }

    public function edit()
    {
        return view('assesments.waiver.edit');
    }

    public function approval($waiverId)
    {
        $waiver = Waiver::findOrFail(decrypt($waiverId));
        $business = Business::find($waiver->business_id);

        return view('assesments.waiver.approval', compact('waiver','business'));
    }
}
