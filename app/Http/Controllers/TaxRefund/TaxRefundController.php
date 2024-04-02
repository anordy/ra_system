<?php

namespace App\Http\Controllers\TaxRefund;

use App\Http\Controllers\Controller;
use App\Models\TaxRefund\TaxRefund;

class TaxRefundController extends Controller
{

    public function init() {
        return view('tax-refund.initial');
    }

    public function index() {
        return view('tax-refund.index');
    }

    public function show($id) {
        $taxRefund = TaxRefund::with(['items'])->findOrFail(decrypt($id), ['id', 'payment_status', 'payment_due_date', 'total_exclusive_tax_amount', 'total_payable_amount', 'importer_name', 'ztn_number', 'phone_number', 'rate']);
        return view('tax-refund.show', compact('taxRefund'));
    }
}