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

    public function viewClosure(){
        return view('business.closure.view');
    }

    public function taxTypeRequests(){
        return view('business.taxtypes.index');
    }

    public function viewTaxTypeRequests(){
        return view('business.taxtypes.show');
    }

    public function viewDeregistration(){
        return view('business.deregister.view');
    }

    public function deregistrations(){
        return view('business.deregister.deregistration-table');
    }

    public function approve(){
        $businessId = 1;
        event(new SendSms('business-registration-approved', $businessId));
        event(new SendMail('business-registration-approved', $businessId));
    }
}
