<?php

namespace App\Http\Controllers\PublicService;

use App\Http\Controllers\Controller;
use App\Models\PublicService\PublicServiceMotor;
use Illuminate\Http\Request;
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
}
