<?php

namespace App\Http\Controllers\Setting;

use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Gate;

class CertificateSignatureController extends Controller
{
    public function index()
    {
        if (!Gate::allows('system-workflow-view')) {
            // abort(403);
        }
        return view('settings.certificate-signature.index');
    }

}
