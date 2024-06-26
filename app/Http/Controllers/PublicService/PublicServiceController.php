<?php

namespace App\Http\Controllers\PublicService;

use App\Enum\CustomMessage;
use App\Http\Controllers\Controller;
use App\Models\PublicService\PublicServiceMotor;
use App\Models\PublicService\PublicServiceReturn;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Gate;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Storage;

class PublicServiceController extends Controller
{
    public function registrations() {
        if (!Gate::allows('public-service-view-registrations')) {
            abort(403);
        }
        return view('public-service.index');
    }

    public function showRegistration($id) {
        if (!Gate::allows('public-service-view-registrations')) {
            abort(403);
        }
        $registration = PublicServiceMotor::findOrFail(decrypt($id));
        return view('public-service.show', compact('registration'));
    }

    public function showFile($path)
    {
        if (!Gate::allows('public-service-view-registrations')) {
            abort(403);
        }
        try {
            return Storage::disk('local')->response(decrypt($path));
        } catch (\Exception $e) {
            report($e);
        }
        return abort(404);
    }

    public function payments(){
        if (!Gate::allows('public-service-view-payments')) {
            abort(403);
        }
        return view('public-service.payments.index');
    }

    public function report(){
        if (!Gate::allows('managerial-public-service-reports-view')) {
            abort(403);
        }

        return view('public-service.report.index');
    }

    public function showPayment($id){
        if (!Gate::allows('public-service-view-payments')) {
            abort(403);
        }
        try {
            $return = PublicServiceReturn::findOrFail(decrypt($id));
            return view('public-service.payments.show', compact('return'));
        } catch (\Exception $exception){
            Log::error('PUBLIC-SERVICE-PAYMENT', [$exception->getMessage()]);
            session()->flash('error', CustomMessage::error());
            return redirect()->back();
        }
    }
}
