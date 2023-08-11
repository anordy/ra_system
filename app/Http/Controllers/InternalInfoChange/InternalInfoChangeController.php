<?php

namespace App\Http\Controllers\InternalInfoChange;

use App\Http\Controllers\Controller;
use App\Models\InternalBusinessUpdate;
use Illuminate\Http\Request;

class InternalInfoChangeController extends Controller
{
    public function index(){
        return view('internal-info-change.index');
    }

    public function initiate(){
        return view('internal-info-change.initiate');
    }

    public function show($internalInfoChangeId){
        $info = InternalBusinessUpdate::select('id', 'type', 'old_values', 'new_values', 'triggered_by', 'business_id', 'location_id', 'status', 'approved_on', 'created_at')->findOrFail(decrypt($internalInfoChangeId));
        return view('internal-info-change.show', compact('info'));
    }
}
