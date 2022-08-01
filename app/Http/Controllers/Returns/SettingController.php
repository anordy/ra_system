<?php

namespace App\Http\Controllers\Returns;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class SettingController extends Controller
{
    public function getStampDutySettings(){
        return view('settings.returns.stamp-duty.index');
    }
}
