<?php

namespace App\Http\Controllers;

use App\Models\Workflow;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Gate;

class WorkflowController extends Controller
{
    public function index()
    {
        if (!Gate::allows('system-workflow-view')) {
            abort(403);
        }
        return view('workflow.index');
    }

    public function show($id)
    {
        if (!Gate::allows('system-workflow-view')) {
            abort(403);
        }
        $workflow = Workflow::findOrFail(decrypt($id));

        return view('workflow.config', compact('workflow'));
    }
}
