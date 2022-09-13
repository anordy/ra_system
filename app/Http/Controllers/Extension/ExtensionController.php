<?php

namespace App\Http\Controllers\Extension;

use App\Http\Controllers\Controller;
use App\Models\Extension\ExtensionRequest;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Gate;
use Illuminate\Support\Facades\Storage;

class ExtensionController extends Controller
{
    public function index(){
        if (!Gate::allows('payment-extension-view')) {
            abort(403);
        }
        return view('extension.index');
    }

    public function show($extensionId){
        if (!Gate::allows('payment-extension-view')) {
            abort(403);
        }
        $extension = ExtensionRequest::findOrFail(decrypt($extensionId));
        $taxReturn = $extension->taxReturn;

        return view('extension.show', compact('extension', 'taxReturn'));
    }

    public function file($file){
        if (!Gate::allows('payment-extension-view')) {
            abort(403);
        }
        if ($file) {
            try {
                return Storage::disk('local-admin')->response(decrypt($file));
            } catch (Exception $e) {
                report($e);
                abort(404);
            }
        }

        return abort(404);
    }
}
