<?php

namespace App\Http\Controllers\Reports\Debts;

use App\Http\Controllers\Controller;

use App\Traits\ReturnReportTrait;
use PDF;

class DebtReportController extends Controller
{
    use ReturnReportTrait;

    public function index()
    {
        return view('reports.debts.index');
    }

    public function preview($parameters)
    {
        $parameters = json_decode(decrypt($parameters), true);
        return view('reports.debts.preview', compact('parameters'));
    }

    public function exportDebtReportPdf($parameters)
    {
        $parameters = json_decode(decrypt($parameters), true);
        $records = $this->getRecords($parameters);
    
        if($parameters['year']=='all'){
            $fileName = $parameters['tax_type_name'].'_'.$parameters['filing_report_type'].'.pdf';
            $title = $parameters['filing_report_type'].' For '.$parameters['tax_type_name'];
        }else{
            $fileName = $parameters['tax_type_name'].'_'.$parameters['filing_report_type'].' - '.$parameters['year'].'.pdf';
            $title = $parameters['filing_report_type'].' For '.$parameters['tax_type_name'].' '.$parameters['year'];
        } 
        $records = $records->get(); 
        $pdf = PDF::loadView('exports.returns.reports.pdf.return', compact('records', 'title', 'parameters'));
        $pdf->setPaper('a4', 'landscape');
        $pdf->setOption(['dpi' => 150, 'defaultFont' => 'sans-serif']);
        return $pdf->download($fileName);
    }
}
