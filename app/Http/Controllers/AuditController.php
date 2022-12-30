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

        return view('audit-trail.index');
    }

    public function show($userId)
    {
        if (!Gate::allows('system-audit-trail-view')) {
            abort(403);
        }

        return view('audit-trail.show', compact('userId'));
    }
}
