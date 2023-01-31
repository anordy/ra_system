<?php

namespace App\Http\Controllers\Payments;

use App\Enum\PaymentStatus;
use App\Http\Controllers\Controller;
use App\Models\BankRecon;
use App\Models\TaxType;
use App\Models\ZmBill;
use App\Models\ZmPayment;
use App\Models\ZmRecon;
use App\Models\ZmReconTran;
use Carbon\Carbon;
use Illuminate\Support\Facades\Gate;
use PDF;

class PaymentsController extends Controller
{

    public function complete(){
        if (!Gate::allows('manage-payments-view')) {
            abort(403);
        }
        $usdDaily = ZmPayment::where('currency', 'USD')
            ->whereDate('trx_time', Carbon::today())
            ->sum('paid_amount');
        $tzsDaily = ZmPayment::where('currency', 'TZS')
            ->whereDate('trx_time', Carbon::today())
            ->sum('paid_amount');
        $tzsWeekly = ZmPayment::where('currency', 'TZS')
            ->whereBetween('trx_time', [Carbon::now()->startOfWeek(), Carbon::now()->endOfWeek()])
            ->sum('paid_amount');
        $usdWeekly = ZmPayment::where('currency', 'USD')
            ->whereBetween('trx_time', [Carbon::now()->startOfWeek(), Carbon::now()->endOfWeek()])
            ->sum('paid_amount');
        return view('payments.complete', compact('usdDaily', 'tzsDaily', 'tzsWeekly', 'usdWeekly'));
    }

    public function pending(){
        if (!Gate::allows('manage-payments-view')) {
            abort(403);
        }
        return view('payments.pending');
    }

    public function cancelled(){
        if (!Gate::allows('manage-payments-view')) {
            abort(403);
        }
        return view('payments.cancelled');
    }

    public function failed(){
        if (!Gate::allows('manage-payments-view')) {
            abort(403);
        }
        return view('payments.failed');
    }

    public function reconEnquire(){
        if (!Gate::allows('manage-payments-view')) {
            abort(403);
        }
        return view('payments.recon-enquiries');
    }

    public function recons($reconId){
        if (!Gate::allows('manage-payments-view')) {
            abort(403);
        }
        $recon = ZmRecon::findOrFail(decrypt($reconId));
        return view('payments.recons', compact('recon'));
    }

    public function show($paymentId){
        $bill = ZmBill::with('bill_payments')->findOrFail(decrypt($paymentId));
        return view('payments.show', compact('bill'));
    }

    public function viewReconTransaction($transactionId) {
        $transaction = ZmReconTran::findOrFail(decrypt($transactionId));
        return view('payments.show-recon-transaction', compact('transaction'));
    }

    public function downloadPendingPaymentsPdf($records,$data){
        $records = decrypt($records);
        $data = decrypt($data);

        $fileName = 'pending_payments' . '_' . $data['currency'] . '.pdf';
        $title = 'pending_payments' . '_' . $data['currency'] . '.pdf';

        $parameters    = $data;
        $pdf = PDF::loadView('exports.payments.pdf.payments', compact('records', 'title', 'parameters'));
        $pdf->setPaper('a4', 'landscape');
        $pdf->setOption(['dpi' => 150, 'defaultFont' => 'sans-serif']);
        return $pdf->download($fileName);
    }

    public function bankRecon(){
        return view('payments.bank-recons.index');
    }

    public function showBankRecon($reconId){
        if (!Gate::allows('manage-payments-view')) {
            abort(403);
        }

        $recon = BankRecon::findOrFail(decrypt($reconId));
        return view('payments.bank-recons.show', compact('recon'));
    }

    public function missingBankRecon(){
        return view('payments.bank-recons.missing-recons');
    }

    public function reconReport(){
        return view('payments.recon-report-filter');
    }

    public function dailyPayments(){
        return view('payments.daily-payments');
    }

    public function dailyPaymentsPerTaxType($payload){
        $data = json_decode(decrypt($payload),true);
        $data['today'] = date('Y-m-d');
        $data['tax_type'] = TaxType::findOrFail($data['tax_type_id']);
        $data['totalTzs'] = $data['tax_type']->getTotalPaymentsPerCurrency('TZS', $data['range_start'], $data['range_end']);
        $data['totalUsd'] = $data['tax_type']->getTotalPaymentsPerCurrency('USD', $data['range_start'], $data['range_end']);
        $start = Carbon::parse($data['range_start'])->startOfDay()->toDateTimeString();
        $end = Carbon::parse($data['range_end'])->endOfDay()->toDateTimeString();
        $data['payments'] = ZmPayment::leftJoin('zm_bills', 'zm_payments.zm_bill_id', 'zm_bills.id')
            ->whereBetween('zm_payments.trx_time', [$start, $end])
            ->where('zm_bills.tax_type_id', $data['tax_type_id'])
            ->get();

        return view('payments.daily-payments-tax-type',compact('data'));
    }

    public function egaCharges(){
        return view('payments.ega-charges');
    }

    public function departmentalReports(){
        return view('payments.departmental-reports');
    }
}
