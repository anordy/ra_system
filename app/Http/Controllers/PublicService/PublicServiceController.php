<?php

namespace App\Http\Controllers\PublicService;

use App\Enum\CustomMessage;
use App\Http\Controllers\Controller;
use App\Models\PublicService\PublicServiceMotor;
use App\Models\PublicService\PublicServiceReturn;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Storage;

class PublicServiceController extends Controller
{

    public function registrations() {
        return view('public-service.index');
    }

    public function showRegistration($id) {
        $registration = PublicServiceMotor::findOrFail(decrypt($id));
        return view('public-service.show', compact('registration'));
    }

    public function showFile($path)
    {
        try {
            return Storage::disk('local')->response(decrypt($path));
        } catch (\Exception $e) {
            report($e);
        }
        return abort(404);
    }

    public function payments(){
        return view('public-service.payments.index');
    }

    public function report(){
        return view('public-service.report.index');
    }

    public function showPayment($id){
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
