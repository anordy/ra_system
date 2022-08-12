<?php

namespace App\Http\Controllers\Returns\ExciseDuty;

use App\Http\Controllers\Controller;
use App\Models\Returns\ExciseDuty\MnoReturn;
use App\Traits\ReturnCardReport;
use Illuminate\Http\Request;

class MnoReturnController extends Controller
{
    use ReturnCardReport;

    public function index(){

        $data = $this->returnCardReport(MnoReturn::class, 'mno', 'mno');

        return view('returns.excise-duty.mno.index', compact('data'));
    }

    public function show($id){
        $return = MnoReturn::find(decrypt($id));
        return view('returns.excise-duty.mno.show',compact('return'));
    }
}
