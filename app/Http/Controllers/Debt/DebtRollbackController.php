<?php

namespace App\Http\Controllers\Debt;

use App\Models\Returns\TaxReturn;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Gate;
use App\Models\TaxAssessments\TaxAssessment;

class DebtRollbackController extends Controller
{
    // For future use if needed to show all rollback histories
    public function index()
    {
        if (!Gate::allows('debt-management-assessment-debt-view')) {
            abort(403);
        }   
       
        return view('debts.rollback.index');
    }

    public function return($tax_return_id)
    {
        if (!Gate::allows('debt-management-debt-rollback')) {
            abort(403);
        }   
        $return_id = decrypt($tax_return_id);
        $tax_return = TaxReturn::findOrFail($return_id);
        $tax_return->return->penalties = $tax_return->return->penalties->merge($tax_return->penalties)->sortBy('penalty_amount');
        return view('debts.rollback.return', compact('tax_return'));
    }

    public function assessment($assessment_id)
    {
        if (!Gate::allows('debt-management-debt-rollback')) {
            abort(403);
        }   
        $assessment_id = decrypt($assessment_id);
        $assessment = TaxAssessment::findOrFail($assessment_id);
        return view('debts.rollback.assessment', compact('assessment'));
    }

    
}
