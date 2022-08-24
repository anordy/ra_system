<?php

namespace App\Http\Controllers\Debt;

use App\Http\Controllers\Controller;
use App\Models\Debts\Debt;
use App\Models\Debts\RecoveryMeasure;

class DebtController extends Controller
{

    public function index()
    {
        return view('debts.index');
    }

    public function overdue()
    {
        return view('debts.overdue.overdue-debts');
    }

    public function recovery($debtId)
    {
        $debtId = decrypt($debtId);
        return view('debts.recovery-measure.assign-recovery-measure', compact('debtId'));
    }

    public function show($debtId)
    {
        $debtId = decrypt($debtId);
        $debt = Debt::findOrFail($debtId);
        $recovery_measures = RecoveryMeasure::where('debt_id', $debtId)->get();
        return view('debts.show', compact('debt', 'recovery_measures'));
    }

}
