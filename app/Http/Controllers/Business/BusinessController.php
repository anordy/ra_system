<?php

namespace App\Http\Controllers\Business;

use App\Events\SendSms;
use App\Events\SendMail;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Gate;
use App\Models\BusinessTaxTypeChange;

class BusinessController extends Controller
{

    public function closure(){
        if (!Gate::allows('temporary-closures-view')) {
            abort(403);
        }
        return view('business.closure.closure-table');
    }

    public function viewClosure(){
        if (!Gate::allows('temporary-closures-view')) {
            abort(403);
        }
        return view('business.closure.view');
    }

    public function taxTypeRequests(){
        if (!Gate::allows('taxtype-change-request-view')) {
            abort(403);
        }
        return view('business.taxtypes.index');
    }

    public function viewTaxTypeRequest($id){
        if (!Gate::allows('taxtype-change-request-view')) {
            abort(403);
        }
        $taxchange = BusinessTaxTypeChange::with('business')->find(decrypt($id));
        return view('business.taxtypes.show', ['taxchange' => $taxchange]);
    }

    public function viewDeregistration(){
        if (!Gate::allows('de-registration-view')) {
            abort(403);
        }
        return view('business.deregister.view');
    }

    public function deregistrations(){
        if (!Gate::allows('de-registration-view')) {
            abort(403);
        }
        return view('business.deregister.deregistration-table');
    }

    public function updatesRequests(){
        if (!Gate::allows('business-update-request-view')) {
            abort(403);
        }
        return view('business.updates.requests');
    }

    public function showRequest($id){
        if (!Gate::allows('business-update-request-view')) {
            abort(403);
        }
        return view('business.updates.show', ['updateId' => $id]);
    }

    public function approve(){
        $businessId = 1;
        event(new SendSms('business-registration-approved', $businessId));
        event(new SendMail('business-registration-approved', $businessId));
    }
}
