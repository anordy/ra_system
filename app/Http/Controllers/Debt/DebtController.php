<?php

namespace App\Http\Controllers\Debt;

use App\Models\Business;
use App\Models\Debts\Debt;
use App\Models\Debts\DebtWaiver;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Gate;
use App\Models\Debts\RecoveryMeasure;
use App\Models\Debts\DebtWaiverAttachment;
use App\Models\Returns\TaxReturn;

class DebtController extends Controller
{

    public function index()
    {
        if (!Gate::allows('debt-management-debts-view')) {
            abort(403);
        }       
        return view('debts.index');
    }

    public function overdue()
    {
        if (!Gate::allows('debt-management-debts-overdue-view')) {
            abort(403);
        }
        return view('debts.overdue.overdue-debts');
    }

    public function waivers()
    {
        if (!Gate::allows('debt-management-waiver-debt-view')) {
            abort(403);
        }
        return view('debts.waivers.index');
    }

    public function approval($waiverId)
    {
        $waiver = DebtWaiver::findOrFail(decrypt($waiverId));
        $files = DebtWaiverAttachment::where('debt_id', $waiver->id)->get();
        return view('debts.waivers.approval', compact('waiver', 'files'));
    }

    public function recovery($debtId)
    {
        $debtId = decrypt($debtId);
        return view('debts.recovery-measure.assign-recovery-measure', compact('debtId'));
    }

    public function sendDemandNotice($debtId)
    {
        $debtId = decrypt($debtId);
        return view('debts.demand-notice.send-demand-notice', compact('debtId'));
    }

    public function show($debtId)
    {
        $debtId = decrypt($debtId);
        $tax_return = TaxReturn::findOrFail($debtId);
        return view('debts.show', compact('tax_return'));
    }

    public function showOverdue($debtId)
    {
        $debtId = decrypt($debtId);
        $tax_return = TaxReturn::findOrFail($debtId);
        return view('debts.show', compact('tax_return'));
    }

}
