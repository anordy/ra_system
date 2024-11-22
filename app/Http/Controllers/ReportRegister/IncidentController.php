<?php

namespace App\Http\Controllers\ReportRegister;

use App\Http\Controllers\Controller;
use Exception;
use Illuminate\Support\Facades\Gate;
use Illuminate\Support\Facades\Storage;

class IncidentController extends Controller
{
    public function summary()
    {
        if (!Gate::allows('report-register-view-incident')) {
            abort(403);
        }

        return view('report-register.incident.summary');
    }

    public function index()
    {
        if (!Gate::allows('report-register-view-incident')) {
            abort(403);
        }

        return view('report-register.incident.index');
    }

    public function staff()
    {
        if (!Gate::allows('report-register-view-incident')) {
            abort(403);
        }

        return view('report-register.incident.staff-incidents');
    }

    public function show($id)
    {
        if (!Gate::allows('report-register-view-incident')) {
            abort(403);
        }

        return view('report-register.incident.show', compact('id'));
    }

    public function settings()
    {
        if (!Gate::allows('report-register-view-settings')) {
            abort(403);
        }

        return view('report-register.settings.show');
    }

    public function subCategory($id)
    {
        if (!Gate::allows('report-register-view-settings')) {
            abort(403);
        }

        return view('report-register.settings.sub-categories', compact('id'));
    }


    public function file($path)
    {
        if (!Gate::allows('report-register-view-incident')) {
            abort(403);
        }

        if ($path) {
            try {
                return Storage::disk('local-admin')->response(decrypt($path));
            } catch (Exception $e) {
                abort(404);
            }
        }

        return abort(404);
    }
}
