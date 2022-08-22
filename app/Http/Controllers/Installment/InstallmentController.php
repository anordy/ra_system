<?php

namespace App\Http\Controllers\Installment;

use App\Http\Controllers\Controller;
use App\Models\Installment\InstallmentRequest;
use Exception;
use Illuminate\Support\Facades\Storage;

class InstallmentController extends Controller
{
    public function index(){
        return view('installment.index');
    }


    public function show($installmentId){
        $installment = InstallmentRequest::findOrFail(decrypt($installmentId));
        $debt = $installment->debt;

        return view('installment.show', compact('installment', 'debt'));
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
