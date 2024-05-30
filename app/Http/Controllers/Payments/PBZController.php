<?php

namespace App\Http\Controllers\Payments;

use App\Http\Controllers\Controller;
use App\Models\PBZReversal;
use App\Models\PBZStatement;
use App\Models\PBZTransaction;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Gate;
use Illuminate\Support\Facades\Log;

class PBZController extends Controller
{
    public function statements(){
        if (!Gate::allows('view-bank-statements')) {
            abort(403);
        }

        return view('payments.pbz.statements');
    }

    public function transactions(){
        if (!Gate::allows('view-bank-transactions')) {
            abort(403);
        }
        return view('payments.pbz.transactions');
    }

    public function reversal($reversal){
        if (!Gate::allows('view-bank-transactions')) {
            abort(403);
        }

        try {
            $reversal = PBZReversal::with('bill')->findOrFail(decrypt($reversal));
            return view('payments.pbz.reversal', compact('reversal'));
        } catch (\Exception $exception){
            Log::error('PBZ-REVERSAL-VIEW', ['EXCEPTION' => $exception]);
            session()->flash('error', 'Something went wrong, please contact your system administrator.');
            return redirect()->back();
        }
    }

    public function payment($payment){
        if (!Gate::allows('view-bank-transactions')) {
            abort(403);
        }

        try {
            $payment = PBZTransaction::with('bill')->findOrFail(decrypt($payment));
            return view('payments.pbz.payment', compact('payment'));
        } catch (\Exception $exception){
            Log::error('PBZ-PAYMENT-VIEW', ['EXCEPTION' => $exception]);
            session()->flash('error', 'Something went wrong, please contact your system administrator.');
            return redirect()->back();
        }
    }

    public function statement($statement){
        if (!Gate::allows('view-bank-statements')) {
            abort(403);
        }
        try {
            $statement = PBZStatement::findOrFail(decrypt($statement));
            return view('payments.pbz.statement', compact('statement'));
        } catch (\Exception $exception){
            Log::error('PBZ-STATEMENT-VIEW', ['EXCEPTION' => $exception]);
            session()->flash('error', 'Something went wrong, please contact your system administrator.');
            return redirect()->back();
        }
    }
}
