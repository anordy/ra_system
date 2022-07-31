<?php

namespace App\Http\Controllers\Returns;

use App\Http\Controllers\Controller;

class HotelLevyReturnController extends Controller
{
   
    public function hotel(){
        return view('settings.returns.hotel');
    }
}
