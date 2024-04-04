<?php

namespace App\Http\Controllers\PublicService;

use App\Enum\CustomMessage;
use App\Http\Controllers\Controller;
use App\Models\PublicService\DeRegistration;
use App\Models\PublicService\TemporaryClosure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Storage;

class DeRegistrationsController extends Controller
{
    // Todo: Add permissions
    public function index(){
        return view('public-service.de-registrations.index');
    }

    public function show($deRegistrationId){
        try {
            $deRegistration = DeRegistration::findOrFail(decrypt($deRegistrationId));
            return view('public-service.de-registrations.show', compact('deRegistration'));
        } catch (\Exception $exception){
            Log::error('PUBLIC-SERVICE-DE-REGISTRATION-VIEW', [$exception->getMessage()]);
            session()->flash('error', CustomMessage::error());
            return redirect()->back();
        }
    }

    public function file($path){
        try {
            return Storage::disk('local')->response(decrypt($path));
        } catch (\Exception $e) {
            Log::error('DE-REGISTRATION-FILE', [$e]);
            report($e);
        }
        return abort(404);
    }
}
