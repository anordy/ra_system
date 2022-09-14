<?php

namespace App\Http\Controllers\Reports\Assessment;

use App\Http\Controllers\Controller;

class AssessmentReportController extends Controller
{
    public function index()
    {
        return view('reports.assessment.index');
    }

    public function preview($parameters)
    {
        $parameters = json_decode(decrypt($parameters), true);
        return view('reports.assessment.preview', compact('parameters'));
    }

}
