<?php


namespace App\Http\Controllers\Verification;

use App\Http\Controllers\Controller;
use App\Models\Returns\StampDuty\StampDutyReturn;
use App\Models\Verification\TaxVerification;
use App\Models\Returns\HotelReturns\HotelReturn;
use App\Models\Returns\Petroleum\PetroleumReturn;

class TaxVerificationApprovalController extends Controller
{
    public function index()
    {
        return view('verification.approval.index');
    }

    public function edit($id){

        $verification = TaxVerification::with('assessment', 'officers')->find(decrypt($id));

        $return = $verification->taxReturn;
        if($return instanceof PetroleumReturn){
            $viewRender = "returns.petroleum.filing.details";
            return view('verification.approval.approval', compact('return', 'verification', 'viewRender'));
        } else if($return instanceof HotelReturn){
            $viewRender = "returns.hotel.details";
            return view('verification.approval.approval', compact('return', 'verification', 'viewRender'));
        } else if($return instanceof StampDutyReturn){
            $viewRender = "returns.stamp-duty.details";
            return view('verification.approval.approval', compact('return', 'verification', 'viewRender'));
        }

    }

    public function show($id){
        $verification = TaxVerification::with('assessment', 'officers')->find(decrypt($id));

        $return = $verification->taxReturn;
        if($return instanceof PetroleumReturn){
            $viewRender = "returns.petroleum.filing.details";
            return view('verification.approval.preview', compact('return', 'verification', 'viewRender'));
        } else if($return instanceof HotelReturn){
            $viewRender = "returns.hotel.details";
            return view('verification.approval.preview', compact('return', 'verification', 'viewRender'));
        } else if ($return instanceof StampDutyReturn){
            $viewRender = "returns.stamp-duty.details";
            return view('verification.approval.preview', compact('return', 'verification', 'viewRender'));
        }
    }
}
