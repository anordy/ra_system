<?php

namespace App\Http\Controllers\Installment;

use App\Http\Controllers\Controller;
use App\Models\Installment\Installment;
use App\Models\Installment\InstallmentRequest;
use Exception;
use Illuminate\Support\Facades\Gate;
use Illuminate\Support\Facades\Storage;

class InstallmentController extends Controller
{
    public function index(){
        if (!Gate::allows('payment-installment-view')) {
            abort(403);
        }
        return view('installment.index');
    }

    public function show($installmentId){
        if (!Gate::allows('payment-installment-view')) {
            abort(403);
        }
        $installment = Installment::findOrFail(decrypt($installmentId));
        $taxReturn = $installment->taxReturn;
        return view('installment.show', compact('installment', 'taxReturn'));
    }

    public function file($file){
        if (!Gate::allows('payment-installment-view')) {
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
