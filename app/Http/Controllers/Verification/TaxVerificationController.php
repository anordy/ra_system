<?php


namespace App\Http\Controllers\Verification;

use App\Http\Controllers\Controller;

use Illuminate\Http\Request;

class TaxVerificationController extends Controller
{
    public function index(Request $request)
    {
        return view('verification.index');
    }

    public function riskIndicators()
    {
        /**
         * TODO
         * 1. Nil Return for three consecutive months
         * 2. All Credit returns
         * 3. Taxpayer who didn’t declare purchases for three consecutive months
         * 4. VAT/hotel Returns for Hotel business whose Purchases/expenses exceed 1/3 of the Sales related to return
         * 5. axpayer who appeared not tally with comparison reports
         * 6. Non-Filer for three Consecutive months
         * 5. Trends of tax paid for the month and other 8 month differ by less than or equal to 10%
         * 6. Sales vs purchases difference is less than or equal to 10%
         * 7. The system shall enable lessee to request the control number for lease payment by filling in DP number and number of years 
         *  (maximum 3 years) as filled during registration
         */
    }
}
