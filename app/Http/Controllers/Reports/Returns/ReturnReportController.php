<?php

namespace App\Http\Controllers\Reports\Returns;

use App\Http\Controllers\Controller;

use App\Traits\ReturnReportTrait;
use Illuminate\Support\Facades\Gate;
use PDF;

class ReturnReportController extends Controller
{
    use ReturnReportTrait;

    public function index()
    {
        if (!Gate::allows('managerial-return-report-vie')) {
            abort(403);
        }
        return view('reports.returns.index');
    }

    public function exportReturnReportPdf($parameters)
    {
        if (!Gate::allows('managerial-report-pdf')) {
            abort(403);
        }
        $parameters = json_decode(decrypt($parameters), true);
        $records = $this->getRecords($parameters);
    
        $fileName = $parameters['tax_type_name'].'_'.$parameters['filing_report_type'].'.pdf';
        $title = $parameters['filing_report_type'].' For '.$parameters['tax_type_name'];
        $records = $records->get(); 
        $pdf = PDF::loadView('exports.returns.reports.pdf.return', compact('records', 'title', 'parameters'));
        $pdf->setPaper('a4', 'landscape');
        $pdf->setOption(['dpi' => 150, 'defaultFont' => 'sans-serif']);
        return $pdf->download($fileName);
    }
}
