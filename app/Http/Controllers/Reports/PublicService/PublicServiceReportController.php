<?php

namespace App\Http\Controllers\Reports\PublicService;

use App\Http\Controllers\Controller;
use App\Traits\PublicServiceReportTrait;
use App\Traits\ReturnReportTrait;
use Illuminate\Support\Facades\Gate;
use PDF;

class PublicServiceReportController extends Controller
{
    use PublicServiceReportTrait;


    public function exportPaymentReportPdf($parameters)
    {
        if (!Gate::allows('managerial-report-pdf')) {
            abort(403);
        }
        $parameters = json_decode(decrypt($parameters), true);
        $records = $this->getRecords($parameters);
    
        $fileName = $parameters['report_type'].'.pdf';

        $records = $records->get(); 
        $pdf = PDF::loadView('exports.public-service.reports.pdf.payment', compact('records',  'parameters'));
        $pdf->setPaper('a4', 'landscape');
        $pdf->setOption(['dpi' => 150, 'defaultFont' => 'sans-serif']);
        return $pdf->download($fileName);
    }

    public function exportRegistrationReportPdf($parameters)
    {
        if (!Gate::allows('managerial-report-pdf')) {
            abort(403);
        }
        $parameters = json_decode(decrypt($parameters), true);
        $records = $this->getRecords($parameters);

        $fileName = $parameters['report_type'].'.pdf';

        $records = $records->get();
        $pdf = PDF::loadView('exports.public-service.reports.pdf.registration', compact('records',  'parameters'));
        $pdf->setPaper('a4', 'landscape');
        $pdf->setOption(['dpi' => 150, 'defaultFont' => 'sans-serif']);
        return $pdf->download($fileName);
    }
}
