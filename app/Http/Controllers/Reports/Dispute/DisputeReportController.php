<?php

namespace App\Http\Controllers\Reports\Dispute;

use App\Http\Controllers\Controller;
use App\Models\TaxType;
use App\Traits\AssessmentReportTrait;
use App\Traits\DisputeReportTrait;
use Illuminate\Support\Facades\Gate;
use PDF;
use Illuminate\Http\Request;

class DisputeReportController extends Controller
{
     use DisputeReportTrait;
    public function index()
    {
        if (!Gate::allows('managerial-dispute-report-vie')) {
            abort(403);
        }
        return view('reports.dispute.index');
    }

    public function preview($parameters)
    {
        if (!Gate::allows('managerial-report-preview')) {
            abort(403);
        }
        $parameters = json_decode(decrypt($parameters), true);
        return view('reports.dispute.preview', compact('parameters'));
    }

    public function exportDisputeReportPdf($parameters)
    {
        if (!Gate::allows('managerial-report-pdf')) {
            abort(403);
        }
        $parameters = json_decode(decrypt($parameters), true);
        $records = $this->getRecords($parameters);
        if ($parameters['tax_type_id'] == 'all') {
            $tax_type_name = 'All';

        } else {
            $tax_type_name = TaxType::findOrFail($parameters['tax_type_id'])->name;
        }

        if ($parameters['year'] == 'all') {
            $fileName = $tax_type_name. '_' . 'Disputes' . '.pdf';
            $title = 'Notice of Dispute'. ' For ' . $tax_type_name;
        } else {
            $fileName = $tax_type_name. '_' . 'Disputes' . ' - ' . $parameters['year'] . '.pdf';
            $title = 'Disputes' . ' For ' . $tax_type_name . '-' . $parameters['year'];
        }
        $records = $records->get();
        $pdf = PDF::loadView('exports.disputes.reports.pdf.dispute', compact('records', 'title', 'parameters'));
        $pdf->setPaper('a4', 'landscape');
        $pdf->setOption(['dpi' => 150, 'defaultFont' => 'sans-serif']);
        return $pdf->download($fileName);
    }
}
