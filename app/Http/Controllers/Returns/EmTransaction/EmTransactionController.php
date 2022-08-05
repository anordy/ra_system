<?php

namespace App\Http\Controllers\Returns\EmTransaction;

use App\Http\Controllers\Controller;
use App\Models\Returns\EmTransaction\EmTransactionReturn;

class EmTransactionController extends Controller
{

    public function index(){
        return view('returns.em-transaction.index');
    }

    public function show($return_id){
        $returnId = decrypt($return_id);
        $return = EmTransactionReturn::findOrFail($returnId);
        return view('returns.em-transaction.show', compact('return', 'returnId'));
    }

}
