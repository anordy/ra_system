<?php

namespace App\Http\Controllers\Tra;

use App\Http\Controllers\Controller;

class TraController extends Controller
{

    public function tins() {
        return view('tra.tins');
    }

    public function chassis() {
        return view('tra.chassis-numbers');
    }

    public function goods() {
        return view('tra.exited-goods');
    }

    public function receipts() {
        return view('tra.vfdms-receipts');
    }
}