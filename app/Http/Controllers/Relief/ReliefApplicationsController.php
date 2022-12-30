<?php


namespace App\Http\Controllers\Relief;
use Illuminate\Support\Facades\Gate;

use App\Http\Controllers\Controller;

use Illuminate\Http\Request;
use Storage;

class ReliefApplicationsController extends Controller
{
    public function index(Request $request)
    {
        if (!Gate::allows('relief-applications-view')) {
            abort(403);
        }
        return view('relief.applications.index');
    }

    public function show($id)
    {
        if (!Gate::allows('relief-applications-view')) {
            abort(403);
        }
        return view('relief.registration.relief-view', compact('id'));
    }

    public function edit($id)
    {
        if (!Gate::allows('relief-applications-edit')) {
            abort(403);
        }
        return view('relief.registration.relief-edit', compact('id'));
    }

    public function getAttachment($path)
    {
        return Storage::disk('local')->response(decrypt($path));
    }
}
