<?php

namespace App\Http\Controllers\Installment;

use App\Http\Controllers\Controller;
use App\Models\Installment\InstallmentRequest;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class InstallmentRequestController extends Controller
{
    public function index(){
        return view('installment.requests.index');
    }

    public function show($installmentId){
        $installment = InstallmentRequest::findOrFail(decrypt($installmentId));
        $debt = $installment->debt;

        return view('installment.requests.show', compact('installment', 'debt'));
    }

    public function file($file){
        if ($file) {
            try {
                return Storage::disk('local-admin')->response(decrypt($file));
            } catch (\Exception $e) {
                report($e);
                abort(404);
            }
        }

        return abort(404);
    }
}
