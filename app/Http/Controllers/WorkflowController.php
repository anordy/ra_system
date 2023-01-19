<?php

namespace App\Http\Controllers;

use App\Models\Workflow;
use Illuminate\Http\Request;

class WorkflowController extends Controller
{
    public function index()
    {
        return view('workflow.index');
    }

    public function show($id)
    {
        $workflow = Workflow::findOrFail(decrypt($id));

        return view('workflow.config', compact('workflow'));
    }
}
