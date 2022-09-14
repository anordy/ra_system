<?php

namespace App\Http\Controllers\Reports\Returns;

use App\Http\Controllers\Controller;

use App\Traits\ReturnReportTrait;
use PDF;

class ReturnReportController extends Controller
{
    use ReturnReportTrait;

    public function index()
    {
        return view('reports.returns.index');
    }

    public function preview($parameters)
    {
        $parameters = json_decode(decrypt($parameters), true);
        return view('reports.assessment.preview', compact('parameters'));
    }

    public function exportReturnReportPdf($parameters)
    {
        $parameters = json_decode(decrypt($parameters), true);
        // dd($parameters);
        $modelData = $this->getModelData($parameters);
        $records = $this->getRecords($modelData['model'], $parameters);
        $for = $parameters['type'] == 'Filing' ? $parameters['filing_report_type'] : $parameters['payment_report_type'];
        $for = str_replace('-', ' ', $for);
        // dd($records);
        if($parameters['year']=='all'){
            $fileName = $modelData['returnName'].' Return Records ('.$for.').pdf';
            $title = $modelData['returnName'].' Return Records ('.$for.')';
        }else{
            $fileName = $modelData['returnName'].' Return Records ('.$for.') - '.$parameters['year'].'.pdf';
            $title = $modelData['returnName'].' Return Records ('.$for.')';
        } 
        $records = $records->get(); 
        $viewName = str_replace(' ','-',strtolower($modelData['returnName']));
        $pdf = PDF::loadView('exports.returns.reports.pdf.'.$viewName, compact('records', 'title', 'parameters'));
        $pdf->setPaper('a4', 'portrait');
        $pdf->setOption(['dpi' => 150, 'defaultFont' => 'sans-serif']);
        return $pdf->download($fileName);
    }
}
