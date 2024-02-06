<?php

namespace App\Http\Controllers\Reports\Business;

use App\Http\Controllers\Controller;
use App\Traits\RegistrationReportTrait;
use Illuminate\Support\Facades\Gate;
use PDF;

class BusinessRegReportController extends Controller
{
    use RegistrationReportTrait;

    public function init(){
        if (!Gate::allows('managerial-business-report-view')) {
            abort(403);
        }
        return view('reports.business.init');
    }

    public function exportBusinessesReportPdf($data)
    {
        if (!Gate::allows('managerial-report-pdf')) {
            abort(403);
        }
        $parameters = json_decode(decrypt($data),true);
        $records = $this->getBusinessBuilder($parameters)->get();

        $pdf = PDF::loadView('exports.business.pdf.business',compact('records', 'parameters'));
        $pdf->setPaper('a4', 'landscape');
        $pdf->setOption(['dpi' => 150, 'defaultFont' => 'sans-serif']);
        return $pdf->download('Business.pdf');
    }

    public function exportBusinessesTaxtypeReportPdf($data)
    {
        if (!Gate::allows('managerial-report-pdf')) {
            abort(403);
        }
        $parameters = json_decode(decrypt($data),true);
        $records = $this->getBusinessBuilder($parameters)->get();
        $recordsData = $records->groupBy('tax_type_id');

        $pdf = PDF::loadView('exports.business.pdf.taxtype',compact('records','recordsData', 'parameters'));
        $pdf->setPaper('a4', 'landscape');
        $pdf->setOption(['dpi' => 150, 'defaultFont' => 'sans-serif']);
        return $pdf->download('taxtype.pdf');
    }

    public function exportBusinessesTaxpayerReportPdf($data)
    {
        if (!Gate::allows('managerial-report-pdf')) {
            abort(403);
        }
        $parameters = json_decode(decrypt($data),true);
        $records = $this->getBusinessBuilder($parameters)->get();
        $recordsData = $records->groupBy('taxpayer_id');
        $pdf = PDF::loadView('exports.business.pdf.taxpayer',compact('records','recordsData', 'parameters'));
        $pdf->setPaper('a4', 'landscape');
        $pdf->setOption(['dpi' => 150, 'defaultFont' => 'sans-serif']);
        return $pdf->download('taxpayer.pdf');
    }

}
