<?php

namespace App\Http\Controllers\Installment;

use App\Http\Controllers\Controller;
use App\Models\Installment\InstallmentRequest;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Gate;
use Illuminate\Support\Facades\Storage;

class InstallmentRequestController extends Controller
{
    public function index(){
        if (!Gate::allows('payment-installment-request-view')) {
            abort(403);
        }
        return view('installment.requests.index');
    }

    public function show($installmentId){
        if (!Gate::allows('payment-installment-request-view')) {
            abort(403);
        }
        $installment = InstallmentRequest::findOrFail(decrypt($installmentId));
        $installable = $installment->installable;

        return view('installment.requests.show', compact('installment', 'installable'));
    }

    public function file($file){
        if (!Gate::allows('payment-installment-request-view')) {
            abort(403);
        }
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
