<?php


namespace App\Http\Controllers\Verification;

use App\Http\Controllers\Controller;
use App\Models\Verification\TaxVerification;
use App\Models\Returns\HotelReturns\HotelReturn;
use App\Models\Returns\Petroleum\PetroleumReturn;

class TaxVerificationVerifiedController extends Controller
{
    public function index()
    {
        return view('verification.verified.index');
    }

    public function show($id)
    {
        $verification = TaxVerification::with('assessment', 'officers')->find(decrypt($id));

        $return = $verification->taxReturn;
        if ($return instanceof PetroleumReturn) {
            $viewRender = "returns.petroleum.filing.details";
            return view('verification.approval.preview', compact('return', 'verification', 'viewRender'));
        } else if($return instanceof HotelReturn){
            $viewRender = "returns.hotel.details";
            return view('verification.approval.preview', compact('return', 'verification', 'viewRender'));
        }
    }
}
