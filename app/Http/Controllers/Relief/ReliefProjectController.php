<?php


namespace App\Http\Controllers\Relief;

use App\Http\Controllers\Controller;
use App\Models\Relief\ReliefProject;
use Illuminate\Support\Facades\Gate;
use Illuminate\Http\Request;

class ReliefProjectController extends Controller
{
    public function index(Request $request)
    {
        if (!Gate::allows('relief-projects-view')) {
            abort(403);
        }
        return view('relief.project.index');
    }

    public function edit($id)
    {
        if (!Gate::allows('relief-projects-edit')) {
            abort(403);
        }
        $project = ReliefProject::find(decrypt($id));
        return view('relief.project_list.index', compact('project'));
    }
}
