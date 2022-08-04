<?php


namespace App\Http\Controllers\Relief;

use App\Http\Controllers\Controller;
use App\Models\Relief\ReliefProject;
use Illuminate\Http\Request;

class ReliefProjectController extends Controller
{
    public function index(Request $request)
    {
        return view('relief.project.index');
    }

    public function edit($id)
    {
        $project = ReliefProject::find(decrypt($id));
        return view('relief.project_list.index', compact('project'));
    }
}
