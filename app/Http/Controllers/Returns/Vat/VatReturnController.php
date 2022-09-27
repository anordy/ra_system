<?php

namespace App\Http\Controllers\Returns\Vat;

use App\Http\Controllers\Controller;
use App\Models\BusinessStatus;
use App\Models\Returns\Vat\VatReturn;
use App\Traits\PaymentsTrait;
use Illuminate\Support\Facades\Gate;

class VatReturnController extends Controller
{
    use  PaymentsTrait;

    public function index()
    {
        if (!Gate::allows('return-vat-return-view')) {
            abort(403);
        }

        $cardOne    = 'returns.vat.vat-card-one';
        $cardTwo    = 'returns.vat.vat-card-two';
        $tableName  = 'returns.vat.vat-return-table';

        return view('returns.vat_returns.index', compact('cardOne', 'cardTwo', 'tableName'));
    }

    public function show($id)
    {
        if (!Gate::allows('return-vat-return-view')) {
            abort(403);
        }
        $return         = VatReturn::query()->findOrFail(decrypt($id));
        $egovernmentFee = $this->getTransactionFee(
            $return->total_amount_due_with_penalties,
            $return->currency,
            2300
        );

        return view('returns.vat_returns.show', compact('return', 'id', 'egovernmentFee'));
    }

    public function config()
    {
        return view('returns.vat_returns.config.index');
    }

    public function configCreate()
    {
        return view('returns.vat_returns.config.create');
    }
}
