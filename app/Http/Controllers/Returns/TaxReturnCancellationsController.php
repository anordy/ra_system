<?php

namespace App\Http\Controllers\Returns;

use App\Http\Controllers\Controller;
use App\Models\Returns\BFO\BfoReturn;
use App\Models\Returns\Chartered\CharteredReturn;
use App\Models\Returns\EmTransactionReturn;
use App\Models\Returns\ExciseDuty\MnoReturn;
use App\Models\Returns\HotelReturns\HotelReturn;
use App\Models\Returns\LumpSum\LumpSumReturn;
use App\Models\Returns\MmTransferReturn;
use App\Models\Returns\Petroleum\PetroleumReturn;
use App\Models\Returns\Port\PortReturn;
use App\Models\Returns\StampDuty\StampDutyReturn;
use App\Models\Returns\TaxReturn;
use App\Models\Returns\TaxReturnCancellation;
use App\Models\Returns\Vat\VatReturn;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Gate;
use Illuminate\Support\Facades\Storage;

class TaxReturnCancellationsController extends Controller
{
    public function index() {
        return view('returns.cancellation.index');
    }

    public function show($id) {
        if (!Gate::allows('tax-returns-vetting-view')) {
            abort(403);
        }

        $cancellation = TaxReturnCancellation::findOrFail(decrypt($id));

        $tax_return = $cancellation->taxReturn ?? $cancellation->trashedTaxReturn;

        $return = $tax_return->return ?? $cancellation->trashedReturn->return;

        if (isset($return->penalties)) {
            $return->penalties = $return->penalties->concat($return->tax_return->penalties ?? [])->sortBy('tax_amount');
        }

        $return_ = null;
        $tax_return_ = null;

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
            $tax_return_ = TaxReturn::where('return_type', PortReturn::class)->where('parent',$tax_return->id)->first();
            if ($tax_return_) {
                $return_ = $tax_return_->return;
                $return_->penalties = $return_->penalties->concat($return_->tax_return->penalties)->sortBy('tax_amount');
            }

            $viewRender = 'returns.port.details';

        } elseif ($return instanceof MnoReturn) {
            $viewRender = 'returns.excise-duty.mno.details';
        }
        elseif ($return instanceof BfoReturn) {
            $viewRender = 'returns.excise-duty.bfo.details';

        } elseif ($return instanceof EmTransactionReturn) {
            $viewRender = 'returns.excise-duty.em-transaction.details';

        } elseif ($return instanceof CharteredReturn) {
            $viewRender = 'returns.chartered.details';

        }  else {
            abort(404);
        }
        return view('returns.cancellation.show', compact('return', 'viewRender', 'tax_return', 'return_','tax_return_', 'cancellation'));
    }

    public function file($path)
    {
        if ($path) {
            try {
                return Storage::disk('local')->response(decrypt($path));
            } catch (\Exception $e) {
                report($e);
                abort(404);
            }
        }

        return abort(404);
    }
}
