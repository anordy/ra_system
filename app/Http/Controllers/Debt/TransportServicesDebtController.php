<?php

namespace App\Http\Controllers\Debt;

use App\Http\Controllers\Controller;
use App\Models\PublicService\PublicServiceReturn;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Gate;

class TransportServicesDebtController extends Controller
{
    public function index()
    {
        if (!Gate::allows('debt-management-transports-debt-view')) {
            abort(403);
        }
        return view('debts.transports.index');
    }

    public function show($debtId)
    {
        if (!Gate::allows('debt-management-transports-debt-view')) {
            abort(403);
        }
        $debtId = decrypt($debtId);
        $return = PublicServiceReturn::findOrFail($debtId);
        return view('debts.transports.show', compact('return'));
    }
}
