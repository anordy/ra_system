<?php

namespace App\Http\Controllers\PublicService;

use App\Enum\CustomMessage;
use App\Http\Controllers\Controller;
use App\Models\PublicService\DeRegistration;
use App\Models\PublicService\TemporaryClosure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Gate;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Storage;

class DeRegistrationsController extends Controller
{
    public function index(){
        if (!Gate::allows('public-service-view-de-registrations')) {
            abort(403);
        }
        return view('public-service.de-registrations.index');
    }

    public function show($deRegistrationId){
        if (!Gate::allows('public-service-view-de-registrations')) {
            abort(403);
        }
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
        dd('HERE');
        if (!Gate::allows('public-service-view-de-registrations')) {
            abort(403);
        }
        try {
            return Storage::disk('local')->response(decrypt($path));
        } catch (\Exception $e) {
            Log::error('DE-REGISTRATION-FILE', [$e]);
            report($e);
        }
        return abort(404);
    }
}
