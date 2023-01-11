<?php

namespace App\Http\Controllers\Reports\Assessment;

use App\Http\Controllers\Controller;
use App\Models\TaxType;
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
        $records    = $this->getRecords($parameters);
        if ($parameters['tax_type_id'] == 'all') {
            $tax_type = 'All';
        } else {
            $tax_type = TaxType::find($parameters['tax_type_id']);
        }

        if ($parameters['year'] == 'all') {
            if ($tax_type == 'All') {
                $fileName = $tax_type . '_' . 'Assessments' . '.pdf';
                $title = 'Notice of Assessments' . ' For ' . $tax_type;
            } else {
                $fileName = $tax_type->name . '_' . 'Assessments' . '.pdf';
                $title = 'Notice of Assessments' . ' For ' . $tax_type->name;
            }
        } else {
            if ($tax_type == 'All') {
                $fileName = $tax_type . '_' . 'Assessments' . ' - ' . $parameters['year'] . '.pdf';
                $title = 'Assessments' . ' For ' . $tax_type . '-' . $parameters['year'];
            } else {
                $fileName = $tax_type->name . '_' . 'Assessments' . ' - ' . $parameters['year'] . '.pdf';
                $title = 'Assessments' . ' For ' . $tax_type->name . '-' . $parameters['year'];
            }
        }
        $records = $records->get();
        $pdf     = PDF::loadView('exports.assessments.reports.pdf.assessment', compact('records', 'title', 'parameters'));
        $pdf->setPaper('a4', 'landscape');
        $pdf->setOption(['dpi' => 150, 'defaultFont' => 'sans-serif']);

        return $pdf->download($fileName);
    }
}
