<?php

namespace App\Http\Controllers\Reports\Payments;

use App\Enum\CustomMessage;
use App\Http\Controllers\Controller;
use App\Traits\PaymentReportTrait;
use Illuminate\Support\Facades\Gate;
use Illuminate\Support\Facades\Log;
use PDF;

class PaymentReportController extends Controller
{
    CONST reportType = 'Payment Reports For ';

    use PaymentReportTrait;

    public function index()
    {
        if (!Gate::allows('managerial-payment-report-view')) {
            abort(403);
        }
        return view('reports.payments.index');
    }

    public function exportPaymentReportPdf($parameters)
    {
        if (!Gate::allows('managerial-report-pdf')) {
            abort(403);
        }

        try {
            $parameters = json_decode(decrypt($parameters), true);
            $records = $this->getRecords($parameters);

            if ($parameters['year'] == 'all') {
                $fileName = $parameters['status'] . ' ' . $parameters['payment_category'] . '.pdf';
                $title = self::reportType. $parameters['status'] . ' ' . $parameters['payment_category'];
            } else {
                $fileName = $parameters['status'] . ' ' . $parameters['payment_category'] . ' - ' . $parameters['year'] . '.pdf';
                $title = self::reportType.$parameters['status'] . ' ' . $parameters['payment_category'] . ' ' . $parameters['year'];
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
        } catch (\Exception $exception){
            Log::error($exception);
            session()->flash('error', CustomMessage::error());
            return back();
        }
    }
}
