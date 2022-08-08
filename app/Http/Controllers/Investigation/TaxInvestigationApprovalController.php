<?php


namespace App\Http\Controllers\Investigation;

use App\Http\Controllers\Controller;
use App\Models\Verification\TaxVerification;
use App\Models\Returns\HotelReturns\HotelReturn;
use App\Models\Returns\Petroleum\PetroleumReturn;

class TaxInvestigationApprovalController extends Controller
{
    public function index()
    {
        return view('investigation.approval.index');
    }

    public function edit($id){

        $investigation = TaxVerification::with('assessment', 'officers')->find(decrypt($id));

        $return = $investigation->taxReturn;
        if($return instanceof PetroleumReturn){
            $viewRender = "returns.petroleum.filing.details";
            return view('investigation.approval.approval', compact('return', 'investigation', 'viewRender'));
        } else if($return instanceof HotelReturn){
            $viewRender = "returns.hotel.details";
            return view('investigation.approval.approval', compact('return', 'investigation', 'viewRender'));
        }


    }

    public function show($id){
        $investigation = TaxVerification::with('assessment', 'officers')->find(decrypt($id));

        $return = $investigation->taxReturn;
        if($return instanceof PetroleumReturn){
            $viewRender = "returns.petroleum.filing.details";
            return view('investigation.approval.preview', compact('return', 'investigation', 'viewRender'));
        } else if($return instanceof HotelReturn){
            $viewRender = "returns.hotel.details";
            return view('investigation.approval.preview', compact('return', 'investigation', 'viewRender'));
        }

    }

    
}
