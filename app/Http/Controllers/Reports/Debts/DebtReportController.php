<?php

namespace App\Http\Controllers\Reports\Debts;

use App\Http\Controllers\Controller;
use App\Traits\DebtReportTrait;
use PDF;

class DebtReportController extends Controller
{
    use DebtReportTrait;

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
            $fileName = $parameters['report_type'].'_'.$parameters['report_type'].'.pdf';
            $title = $parameters['report_type'].' For '.$parameters['report_type'];
        }else{
            $fileName = $parameters['report_type'].'_'.$parameters['report_type'].' - '.$parameters['year'].'.pdf';
            $title = $parameters['report_type'].' For '.$parameters['report_type'].' '.$parameters['year'];
        } 

        if ($parameters['report_type'] === 'Returns') {
            $records = $records->get(); 
            $pdf = PDF::loadView('exports.debts.pdf.return', compact('records', 'title', 'parameters'));
            $pdf->setPaper('a4', 'landscape');
            $pdf->setOption(['dpi' => 150, 'defaultFont' => 'sans-serif']);
            return $pdf->download($fileName);
        } else if ($parameters['report_type'] === 'Assessments') {
            $records = $records->get(); 
            $pdf = PDF::loadView('exports.debts.pdf.assessment', compact('records', 'title', 'parameters'));
            $pdf->setPaper('a4', 'landscape');
            $pdf->setOption(['dpi' => 150, 'defaultFont' => 'sans-serif']);
            return $pdf->download($fileName);
        } else if ($parameters['report_type'] === 'Installment') {
            $records = $records->get(); 
            $pdf = PDF::loadView('exports.debts.pdf.installments', compact('records', 'title', 'parameters'));
            $pdf->setPaper('a4', 'landscape');
            $pdf->setOption(['dpi' => 150, 'defaultFont' => 'sans-serif']);
            return $pdf->download($fileName);
        } else if ($parameters['report_type'] === 'Waiver') {
            $records = $records->get(); 
            $pdf = PDF::loadView('exports.debts.pdf.waiver', compact('records', 'title', 'parameters'));
            $pdf->setPaper('a4', 'landscape');
            $pdf->setOption(['dpi' => 150, 'defaultFont' => 'sans-serif']);
            return $pdf->download($fileName);
        } else if ($parameters['report_type'] === 'Demand-Notice') {
            $records = $records->get(); 
            $pdf = PDF::loadView('exports.debts.pdf.demand-notice', compact('records', 'title', 'parameters'));
            $pdf->setPaper('a4', 'landscape');
            $pdf->setOption(['dpi' => 150, 'defaultFont' => 'sans-serif']);
            return $pdf->download($fileName);
        }

    }
}
