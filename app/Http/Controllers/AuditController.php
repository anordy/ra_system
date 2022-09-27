<?php

namespace App\Http\Controllers;

use Illuminate\Support\Facades\Gate;

class AuditController extends Controller
{
    public function index()
    {
        if (!Gate::allows('system-audit-trail-view')) {
            abort(403);
        }

        return view('audits');
    }
}
