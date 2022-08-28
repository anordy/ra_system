<?php

namespace App\Http\Controllers\Debt;

use App\Models\Debts\Debt;
use App\Http\Controllers\Controller;

class VerificationDebtController extends Controller
{

    public function index()
    {
        return view('debts.verifications.index');
    }


    public function show($id)
    {
        $id = decrypt($id);
        $debt = Debt::findOrFail($id);
        $assesment = $debt->debt_type::find($debt->debt_id);

        return view('debts.verifications.show', compact('assesment', 'id', 'debt'));
    }
    
}
