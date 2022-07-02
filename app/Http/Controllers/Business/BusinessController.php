<?php

namespace App\Http\Controllers\Business;

use App\Http\Controllers\Controller;

class BusinessController extends Controller
{

    public function closure(){
        return view('business.closure.closure-table');
    }

}
