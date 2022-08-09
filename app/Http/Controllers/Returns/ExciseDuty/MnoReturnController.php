<?php

namespace App\Http\Controllers\Returns\ExciseDuty;

use App\Http\Controllers\Controller;
use App\Models\Returns\ExciseDuty\MnoReturn;
use Illuminate\Http\Request;

class MnoReturnController extends Controller
{
    public function index(){
        return view('returns.excise-duty.mno.index');
    }

    public function show($id){
        $return = MnoReturn::find(decrypt($id));
        return view('returns.excise-duty.mno.show',compact('return'));
    }
}
