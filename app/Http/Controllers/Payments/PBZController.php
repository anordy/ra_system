<?php

namespace App\Http\Controllers\Payments;

use App\Http\Controllers\Controller;
use App\Models\PBZReversal;
use App\Models\PBZStatement;
use App\Models\PBZTransaction;
use Illuminate\Http\Request;

class PBZController extends Controller
{
    public function statements(){
        return view('payments.pbz.statements');
    }

    public function transactions(){
        return view('payments.pbz.transactions');
    }

    public function reversal($reversal){
        $reversal = PBZReversal::with('bill')->findOrFail(decrypt($reversal));
        return view('payments.pbz.reversal', compact('reversal'));
    }

    public function payment($payment){
        $payment = PBZTransaction::with('bill')->findOrFail(decrypt($payment));
        return view('payments.pbz.payment', compact('payment'));
    }

    public function statement($statement){
        $statement = PBZStatement::findOrFail(decrypt($statement));
        return view('payments.pbz.statement', compact('statement'));
    }
}
