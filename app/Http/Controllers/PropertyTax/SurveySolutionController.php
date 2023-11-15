<?php

namespace App\Http\Controllers\PropertyTax;

use App\Http\Controllers\Controller;

class SurveySolutionController extends Controller
{

    public function init() {
        return view('property-tax.survey-solution.initial');
    }
}