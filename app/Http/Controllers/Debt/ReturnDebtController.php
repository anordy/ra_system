<?php

namespace App\Http\Controllers\Debt;

use PDF;
use App\Models\Debts\DebtWaiver;
use App\Models\Returns\TaxReturn;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Gate;
use App\Models\Debts\DebtWaiverAttachment;
use App\Models\Debts\DemandNotice;
use Carbon\Carbon;

class ReturnDebtController extends Controller
{

    public function index()
    {
        if (!Gate::allows('debt-management-debts-view')) {
            abort(403);
        }       
        return view('debts.returns.index');
    }

    public function waivers()
    {
        if (!Gate::allows('debt-management-waiver-debt-view')) {
            abort(403);
        }
        return view('debts.returns.waivers.index');
    }

    public function approval($waiverId)
    {
        if (!Gate::allows('debt-management-debts-waive')) {
            abort(403);
        }
        $waiver = DebtWaiver::findOrFail(decrypt($waiverId));
        $files = DebtWaiverAttachment::where('waiver_id', $waiver->id)->get();
        return view('debts.returns.waivers.approval', compact('waiver', 'files'));
    }

    public function recovery($debtId)
    {
        if (!Gate::allows('debt-management-debts-recovery-measure')) {
            abort(403);
        }
        $debtId = decrypt($debtId);
        return view('debts.recovery-measure.assign-recovery-measure', compact('debtId'));
    }

    public function showReturnDemandNotice($demandNoticeId)
    {
        $demandNoticeId = decrypt($demandNoticeId);
        $demandNotice = DemandNotice::findOrFail($demandNoticeId);
        $now = Carbon::parse($demandNotice->sent_on)->format('d/m/Y');

        $tax_return = $demandNotice->debt;
        $paid_within_days = $demandNotice->paid_within_days;
        $next_notify_days = $demandNotice->next_notify_days;

        $pdf = PDF::loadView('debts.demand-notice.return-demand-notice', compact('tax_return', 'now', 'paid_within_days'));
        $pdf->setPaper('a4', 'portrait');
        $pdf->setOption(['dpi' => 150, 'defaultFont' => 'sans-serif']);

        return $pdf->stream();
    }

    public function showAssessmentDemandNotice($demandNoticeId)
    {
        $demandNoticeId = decrypt($demandNoticeId);
        $demandNotice = DemandNotice::findOrFail($demandNoticeId);
        $now = Carbon::parse($demandNotice->sent_on)->format('d/m/Y');

        $debt = $demandNotice->debt;
        $paid_within_days = $demandNotice->paid_within_days;
        $next_notify_days = $demandNotice->next_notify_days;

        $pdf = PDF::loadView('debts.demand-notice.assessment-demand-notice', compact('debt', 'now', 'paid_within_days'));
        $pdf->setPaper('a4', 'portrait');
        $pdf->setOption(['dpi' => 150, 'defaultFont' => 'sans-serif']);

        return $pdf->stream();

    }

    public function show($debtId)
    {
        if (!Gate::allows('debt-management-debts-view')) {
            abort(403);
        }     
        $debtId = decrypt($debtId);
        $tax_return = TaxReturn::findOrFail($debtId);
        return view('debts.returns.show', compact('tax_return'));
    }

    public function showWaiver($debtId)
    {
        if (!Gate::allows('debt-management-waiver-debt-view')) {
            abort(403);
        }     
        $debtId = decrypt($debtId);
        $tax_return = TaxReturn::findOrFail($debtId);
        return view('debts.returns.waivers.show', compact('tax_return'));
    }

    public function showOverdue($debtId)
    {
        if (!Gate::allows('debt-management-debts-view')) {
            abort(403);
        }     
        $debtId = decrypt($debtId);
        $tax_return = TaxReturn::findOrFail($debtId);
        return view('debts.returns.show', compact('tax_return'));
    }
    
}
