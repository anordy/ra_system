<?php


namespace App\Http\Controllers\Audit;

use App\Http\Controllers\Controller;
use App\Models\Verification\TaxVerification;
use App\Models\Returns\HotelReturns\HotelReturn;
use App\Models\Returns\Petroleum\PetroleumReturn;

class TaxAuditApprovalController extends Controller
{
    public function index()
    {
        return view('audit.approval.index');
    }

    public function edit($id){

        $audit = TaxVerification::with('assessment', 'officers')->find(decrypt($id));

        $return = $audit->taxReturn;
        if($return instanceof PetroleumReturn){
            $viewRender = "returns.petroleum.filing.details";
            return view('audit.approval.approval', compact('return', 'audit', 'viewRender'));
        } else if($return instanceof HotelReturn){
            $viewRender = "returns.hotel.details";
            return view('audit.approval.approval', compact('return', 'audit', 'viewRender'));
        }

    }

    public function show($id){
        $audit = TaxVerification::with('assessment', 'officers')->find(decrypt($id));

        $return = $audit->taxReturn;
        if($return instanceof PetroleumReturn){
            $viewRender = "returns.petroleum.filing.details";
            return view('audit.approval.preview', compact('return', 'audit', 'viewRender'));
        } else if($return instanceof HotelReturn){
            $viewRender = "returns.hotel.details";
            return view('audit.approval.preview', compact('return', 'audit', 'viewRender'));
        }
    }

    
}
