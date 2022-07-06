<?php

namespace App\Http\Controllers\Business;

use App\Events\SendMail;
use App\Events\SendSms;
use App\Http\Controllers\Controller;

class BusinessController extends Controller
{

    public function closure(){
        return view('business.closure.closure-table');
    }

    public function approve(){
        $businessId = 1;
        event(new SendSms('business-registration-approved', $businessId));
        event(new SendMail('business-registration-approved', $businessId));
    }
}
