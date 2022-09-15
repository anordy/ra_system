<?php

namespace App\Http\Controllers\Reports\Assessment;

use App\Http\Controllers\Controller;
use App\Traits\AssessmentReportTrait;
use PDF;

class AssessmentReportController extends Controller
{
    use AssessmentReportTrait;
    public function index()
    {
        return view('reports.assessment.index');
    }

    public function preview($parameters)
    {
        $parameters = json_decode(decrypt($parameters), true);
        return view('reports.assessment.preview', compact('parameters'));
    }

    public function exportAssessmentReportPdf($parameters)
    {
        $parameters = json_decode(decrypt($parameters), true);
        $records = $this->getRecords($parameters);

        if ($parameters['year'] == 'all') {
            $fileName = 'Verification' . '_' . 'Assessments' . '.pdf';
            // $fileName = $parameters['tax_type_name'] . '_' . $parameters['filing_report_type'] . '.pdf';
            // $title = $parameters['filing_report_type'] . ' For ' . $parameters['tax_type_name'];
            $title = 'Notice Of Assessments';
        } else {
            // $fileName = $parameters['tax_type_name'] . '_' . $parameters['filing_report_type'] . ' - ' . $parameters['year'] . '.pdf';
            // $title = $parameters['filing_report_type'] . ' For ' . $parameters['tax_type_name'] . ' ' . $parameters['year'];
            $fileName = 'Verification' . '_' . 'Assessments' . '.pdf';
            $title = 'Notice Of Assessments';

        }
        $records = $records->get();
        $pdf = PDF::loadView('exports.assessments.reports.pdf.assessment', compact('records', 'title', 'parameters'));
        $pdf->setPaper('a4', 'landscape');
        $pdf->setOption(['dpi' => 150, 'defaultFont' => 'sans-serif']);
        return $pdf->download($fileName);
    }

}
