<?php

namespace App\Http\Controllers\Setting;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class ApprovalLevelController extends Controller
{
    public function index()
    {
        return view('settings.approval-levels');
    }
}
