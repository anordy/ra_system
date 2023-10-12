<?php

namespace App\Http\Controllers\Reports\Payments;

use App\Http\Controllers\Controller;

use App\Traits\PaymentReportTrait;
use Illuminate\Support\Facades\Gate;
use PDF;

class PaymentReportController extends Controller
{
    use PaymentReportTrait;

    public function index()
    {
        if (!Gate::allows('managerial-payment-report-vie')) {
            abort(403);
        }
        return view('reports.payments.index');
    }

    public function exportPaymentReportPdf($parameters)
    {
        if (!Gate::allows('managerial-report-pdf')) {
            abort(403);
        }
        $parameters = json_decode(decrypt($parameters), true);
        $records = $this->getRecords($parameters);

        if ($parameters['year'] == 'all') {
            $fileName = $parameters['status'] . ' ' . $parameters['payment_category'] . '.pdf';
            $title = 'Payment Reports For '. $parameters['status'] . ' ' . $parameters['payment_category'];
        } else {
            $fileName = $parameters['status'] . ' ' . $parameters['payment_category'] . ' - ' . $parameters['year'] . '.pdf';
            $title = 'Payment Reports For '.$parameters['status'] . ' ' . $parameters['payment_category'] . ' ' . $parameters['year'];
        }
        $records = $records->get();
        if ($parameters['payment_category'] == 'returns')
        {
            $pdf = PDF::loadView('exports.payments.reports.pdf.return', compact('records', 'title', 'parameters'));
        }
        else{
            $pdf = PDF::loadView('exports.payments.reports.pdf.consultant', compact('records', 'title', 'parameters'));
        }
        $pdf->setPaper('a4', 'landscape');
        $pdf->setOption(['dpi' => 150, 'defaultFont' => 'sans-serif']);
        return $pdf->download($fileName);
    }
}
