<?php

namespace App\Http\Controllers\Tra;

use App\Http\Controllers\Controller;
use App\Models\Tra\ChassisNumber;
use App\Models\Tra\EfdmsReceipt;
use App\Models\Tra\ExitedGood;
use App\Models\Tra\Tin;
use Illuminate\Support\Facades\Gate;

class TraController extends Controller
{

    public function tins() {
        if (!Gate::allows('tra-information-view-tin')) {
            abort(403);
        }
        return view('tra.tins');
    }

    public function showTin(string $id) {
        if (!Gate::allows('tra-information-view-tin')) {
            abort(403);
        }
        $tin = Tin::findOrFail(decrypt($id));
        return view('tra.tins-show', compact('tin'));
    }

    public function chassis() {
        if (!Gate::allows('tra-information-view-chassis-number')) {
            abort(403);
        }
        return view('tra.chassis-numbers');
    }

    public function showChassis(string $id) {
        if (!Gate::allows('tra-information-view-chassis-number')) {
            abort(403);
        }
        $chassis = ChassisNumber::findOrFail(decrypt($id));
        return view('tra.chassis-numbers-show', compact('chassis'));
    }

    public function goods() {
        if (!Gate::allows('tra-information-view-exited-good')) {
            abort(403);
        }
        return view('tra.exited-goods');
    }

    public function showGoods(string $id) {
        if (!Gate::allows('tra-information-view-exited-good')) {
            abort(403);
        }
        $goods = ExitedGood::findOrFail(decrypt($id));
        return view('tra.exited-goods-show', compact('goods'));
    }

    public function receipts() {
        if (!Gate::allows('tra-information-view-efdms-receipt')) {
            abort(403);
        }
        return view('tra.vfdms-receipts');
    }

    public function showReceipt(string $id) {
        if (!Gate::allows('tra-information-view-efdms-receipt')) {
            abort(403);
        }
        $receipt = EfdmsReceipt::findOrFail(decrypt($id));
        return view('tra.vfdms-receipts-show', compact('receipt'));
    }
}