<?php

namespace App\Http\Controllers\Extension;

use App\Http\Controllers\Controller;
use App\Models\Extension\ExtensionRequest;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class ExtensionController extends Controller
{
    public function index(){
        return view('extension.index');
    }

    public function show($extensionId){
        $extension = ExtensionRequest::findOrFail(decrypt($extensionId));
        $debt = $extension->debt;

        return view('extension.show', compact('extension', 'debt'));
    }

    public function file($file){
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
