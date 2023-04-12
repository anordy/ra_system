<?php

namespace App\Http\Controllers\Vetting;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Models\Returns\Vat\VatReturn;
use App\Models\Returns\Port\PortReturn;
use App\Models\Returns\MmTransferReturn;
use App\Models\Returns\ExciseDuty\MnoReturn;
use App\Models\Verification\TaxVerification;
use App\Models\Returns\LumpSum\LumpSumReturn;
use App\Models\Returns\HotelReturns\HotelReturn;
use App\Models\Returns\Petroleum\PetroleumReturn;
use App\Models\Returns\StampDuty\StampDutyReturn;
use App\Models\Returns\TaxReturn;

class TaxReturnVettingController extends Controller
{
    public function index() {
        return view('vetting.index');
    }

    public function show($return_id) {

        $tax_return = TaxReturn::findOrFail(decrypt($return_id));

        $return = $tax_return->return;

        if ($return instanceof PetroleumReturn) {
            $viewRender = 'returns.petroleum.filing.details';

        } elseif ($return instanceof LumpSumReturn) {
            $viewRender = 'returns.lump-sum.details';

        } elseif ($return instanceof HotelReturn) {
            $viewRender = 'returns.hotel.details';

        } elseif ($return instanceof StampDutyReturn) {
            $viewRender = 'returns.stamp-duty.details';

        } elseif ($return instanceof VatReturn) {
            $viewRender = 'returns.vat_returns.details';

        } elseif ($return instanceof MmTransferReturn) {
            $viewRender = 'returns.excise-duty.mobile-money-transfer.details';

        } elseif ($return instanceof PortReturn) {
            $viewRender = 'returns.port.details';

        } elseif ($return instanceof MnoReturn) {
            $viewRender = 'returns.excise-duty.mno.details';

        }

        return view('vetting.show', compact('return', 'viewRender', 'tax_return'));
    }
}
