<?php

namespace App\Http\Controllers\TaxpayerLedger;

use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Gate;

class TaxpayerLedgerController extends Controller
{

    public function search() {
        if (!Gate::allows('view-taxpayer-ledgers')) {
            abort(403);
        }
        return view('taxpayer-ledger.search');
    }

    public function show($businessLocationId, $taxTypeId) {
        if (!Gate::allows('view-taxpayer-ledgers')) {
            abort(403);
        }
        return view('taxpayer-ledger.show');
    }
}
