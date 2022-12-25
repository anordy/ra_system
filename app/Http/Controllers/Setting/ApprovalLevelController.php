<?php

namespace App\Http\Controllers\Setting;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Gate;

class ApprovalLevelController extends Controller
{
    public function index()
    {
        if (!Gate::allows('setting-approval-level-view')) {
            abort(403);
        }
        return view('settings.approval-levels');
    }
}
