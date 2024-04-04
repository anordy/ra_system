<?php

namespace App\Http\Controllers\Reports\Assessment;

use App\Enum\CustomMessage;
use App\Http\Controllers\Controller;
use App\Models\TaxType;
use App\Traits\AssessmentReportTrait;
use Illuminate\Support\Facades\Gate;
use Illuminate\Support\Facades\Log;
use PDF;

class AssessmentReportController extends Controller
{
    use AssessmentReportTrait;

    CONST reportType = 'Assessments';

    public function index()
    {
        if (!Gate::allows('managerial-assessment-report-view')) {
            abort(403);
        }
        return view('reports.assessment.index');
    }

    public function preview($parameters)
    {
        if (!Gate::allows('managerial-report-preview')) {
            abort(403);
        }

        try {
            $parameters = json_decode(decrypt($parameters), true);
            return view('reports.assessment.preview', compact('parameters'));
        } catch (\Exception $exception){
            Log::error($exception);
            session()->flash('error', CustomMessage::error());
            return back();
        }
    }

    public function exportAssessmentReportPdf($parameters)
    {
        if (!Gate::allows('managerial-report-pdf')) {
            abort(403);
        }

        try {
            $parameters = json_decode(decrypt($parameters), true);
            $records    = $this->getRecords($parameters);
            if ($parameters['tax_type_id'] == 'all') {
                $tax_type = 'All';
            } else {
                $tax_type = TaxType::findOrFail($parameters['tax_type_id']);
            }

            if ($parameters['year'] == 'all') {
                if ($tax_type == 'All') {
                    $fileName = $tax_type . '_' . self::reportType . '.pdf';
                    $title = self::reportType . ' For ' . $tax_type;
                } else {
                    $fileName = $tax_type->name . '_' . self::reportType . '.pdf';
                    $title = self::reportType . ' For ' . $tax_type->name;
                }
            } else {
                if ($tax_type == 'All') {
                    $fileName = $tax_type . '_' . self::reportType . ' - ' . $parameters['year'] . '.pdf';
                    $title = self::reportType . ' For ' . $tax_type . '-' . $parameters['year'];
                } else {
                    $fileName = $tax_type->name . '_' . self::reportType . ' - ' . $parameters['year'] . '.pdf';
                    $title = self::reportType . ' For ' . $tax_type->name . '-' . $parameters['year'];
                }
            }
            $records = $records->get();
            $pdf     = PDF::loadView('exports.assessments.reports.pdf.assessment', compact('records', 'title', 'parameters'));
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
