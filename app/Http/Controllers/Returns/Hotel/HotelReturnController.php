<?php

namespace App\Http\Controllers\Returns\Hotel;

use App\Http\Controllers\Controller;
use App\Models\Returns\HotelReturns\HotelReturn;
use App\Traits\ReturnCardReport;

class HotelReturnController extends Controller
{
    use ReturnCardReport;

    public function index(){
        
        $data = $this->returnCardReport(HotelReturn::class, 'hotel', 'hotel_return');

        return view('returns.hotel.index', compact('data'));
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
