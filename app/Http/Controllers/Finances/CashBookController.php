<?php

namespace App\Http\Controllers\Finances;

use App\Enum\LeaseStatus;
use App\Enum\ReturnCategory;
use App\Http\Controllers\Controller;
use App\Models\Business;
use App\Models\Investigation\TaxInvestigation;
use App\Models\LandLeaseDebt;
use App\Models\Returns\ReturnStatus;
use App\Models\Returns\TaxReturn;
use App\Models\TaxAssessments\TaxAssessment;
use App\Models\TaxAudit\TaxAudit;
use App\Models\ZmPayment;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Gate;

class CashBookController extends Controller
{
    public function index(){
        if (!Gate::allows('view-cash-book')) {
            abort(403);
        }

        $accounts = ZmPayment::select('ctr_acc_num', 'currency', 'psp_name')
            ->groupBy('ctr_acc_num', 'currency', 'psp_name')
            ->orderBy('currency', 'ASC')
            ->get();

        return view('finance.cashbook.index', compact('accounts'));
    }

    public function show($accountNum){
        if (!Gate::allows('view-cash-book')) {
            abort(403);
        }

        $accountNumber = decrypt($accountNum);
        $account = ZmPayment::where('ctr_acc_num', $accountNumber)->first();

        return view('finance.cashbook.show', compact('accountNumber', 'account'));
    }

}
