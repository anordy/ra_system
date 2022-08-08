<?php


namespace App\Http\Controllers\Relief;

use App\Http\Controllers\Controller;

use Illuminate\Http\Request;
use Storage;

class ReliefApplicationsController extends Controller
{
    public function index(Request $request)
    {
        return view('relief.applications.index');
    }

    public function show($id)
    {
        return view('relief.registration.relief-view', compact('id'));
    }

    public function edit($id)
    {
        return view('relief.registration.relief-edit', compact('id'));
    }

    public function getAttachment($path)
    {
        return Storage::disk('local-admin')->response(decrypt($path));
    }
}
