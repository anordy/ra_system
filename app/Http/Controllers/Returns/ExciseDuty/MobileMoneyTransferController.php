<?php

namespace App\Http\Controllers\Returns\ExciseDuty;

use App\Http\Controllers\Controller;
use App\Models\Returns\MmTransferReturn;
use Illuminate\Http\Request;

class MobileMoneyTransferController extends Controller
{
    public function index()
    {
        return view('returns.excise-duty.mobile-money-transfer.index');
    }

    public function show($return_id)
    {
        $return = MmTransferReturn::query()->findOrFail(decrypt($return_id));
        return view('returns.excise-duty.mobile-money-transfer.show', compact('return','return_id'));
    }
}
