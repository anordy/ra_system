<?php

namespace App\Http\Controllers\PropertyTax;

use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Gate;

class SurveySolutionController extends Controller
{

    public function init() {
        if (!Gate::allows('survey-solution-registration')) {
            abort(403);
        }
        return view('property-tax.survey-solution.initial');
    }
}