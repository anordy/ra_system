<?php

namespace App\Http\Controllers\Returns\LumpSum;

use App\Http\Controllers\Controller;
use App\Models\Returns\LumpSum\LumpSumReturn;
use Illuminate\Support\Facades\Gate;

class LumpSumReturnController extends Controller
{
    public function index()
    {
        if (!Gate::allows('return-lump-sum-payment-return-view')) {
            abort(403);
        }

        $tableName = 'returns.lump-sum.lump-sum-returns-table';
        $cardOne   = 'returns.lump-sum.lump-sum-card-one';
        $cardTwo   = 'returns.lump-sum.lump-sum-card-two';

        return view('returns.lump-sum.history', compact('tableName', 'cardOne', 'cardTwo'));
    }
 
    public function view($row)
    {
        if (!Gate::allows('return-lump-sum-payment-return-view')) {
            abort(403);
        }
        $id = decrypt($row);
       
        $return = LumpSumReturn::findOrFail($id);

        return view('returns.lump-sum.view', compact('return'));
    }
}
