<?php

namespace App\Http\Controllers\Returns\Hotel;

use App\Http\Controllers\Controller;
use App\Models\Returns\HotelReturns\HotelReturn;

class HotelReturnController extends Controller
{
    

    public function index(){
        return view('returns.hotel.index');
    }

    public function show($return_id){
        $returnId = decrypt($return_id);
        $return = HotelReturn::findOrFail($returnId);
        return view('returns.hotel.show', compact('return'));
    }

    public function adjust($return_id){
        $returnId = decrypt($return_id);
        return view('returns.hotel.adjust', compact('returnId'));
    }

}
