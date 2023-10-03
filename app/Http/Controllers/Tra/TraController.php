<?php

namespace App\Http\Controllers\Tra;

use App\Http\Controllers\Controller;
use App\Models\Tra\ChassisNumber;
use App\Models\Tra\EfdmsReceipt;
use App\Models\Tra\ExitedGood;
use App\Models\Tra\Tin;

class TraController extends Controller
{

    public function tins() {
        return view('tra.tins');
    }

    public function showTin(string $id) {
        $tin = Tin::findOrFail(decrypt($id));
        return view('tra.tins-show', compact('tin'));
    }

    public function chassis() {
        return view('tra.chassis-numbers');
    }

    public function showChassis(string $id) {
        $chassis = ChassisNumber::findOrFail(decrypt($id));
        return view('tra.chassis-numbers-show', compact('chassis'));
    }

    public function goods() {
        return view('tra.exited-goods');
    }

    public function showGoods(string $id) {
        $goods = ExitedGood::findOrFail(decrypt($id));
        return view('tra.exited-goods-show', compact('goods'));
    }

    public function receipts() {
        return view('tra.vfdms-receipts');
    }

    public function showReceipt(string $id) {
        $receipt = EfdmsReceipt::findOrFail(decrypt($id));
        return view('tra.vfdms-receipts-show', compact('receipt'));
    }
}