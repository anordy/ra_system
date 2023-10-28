<?php

namespace App\Http\Controllers\PropertyTax;

use App\Http\Controllers\Controller;
use App\Http\Livewire\PropertyTax\PropertyTaxPayment;
use App\Models\PropertyTax\PaymentExtension;
use App\Models\Taxpayer;
use Illuminate\Http\Request;

class PaymentExtensionController extends Controller
{
    //
    public function index() {
        return view('property-tax.payment-extension.index');
    }

    public function show(string $id) {
        $paymentExtension = PaymentExtension::findOrFail(decrypt($id));
        $requestedBy = $paymentExtension->requested_by_type::find($paymentExtension->requested_by_id);
        if($paymentExtension->requested_by_type == Taxpayer::class){
            $requestedByName = $requestedBy->first_name .' '. $requestedBy->middle_name .' '. $requestedBy->last_name;
        } else {
            $requestedByName = $requestedBy->name;
        }
        return view('property-tax.payment-extension.show', compact('paymentExtension', 'requestedByName'));
    }
}
