<?php

namespace App\Http\Controllers\Returns\ExciseDuty;

use App\Http\Controllers\Controller;
use App\Models\Returns\MmTransferReturn;
use App\Traits\ReturnCardReport;
use Illuminate\Http\Request;

class MobileMoneyTransferController extends Controller
{
    use ReturnCardReport;

    public function index()
    {
        $data = $this->returnCardReport(MmTransferReturn::class, 'mm_transfer', 'mm_transfer');

        return view('returns.excise-duty.mobile-money-transfer.index', compact('data'));
    }

    public function show($return_id)
    {
        $return = MmTransferReturn::query()->findOrFail(decrypt($return_id));
        return view('returns.excise-duty.mobile-money-transfer.show', compact('return','return_id'));
    }
}
