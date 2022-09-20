<?php

namespace App\Http\Controllers\Reports\Claims;

use App\Http\Controllers\Controller;
use App\Traits\ClaimReportTrait;
use Illuminate\Http\Request;
use PDF;

class ClaimReportController extends Controller
{
    use ClaimReportTrait;

    public function init(){
        return view('reports.claims.index');
    }

    public function preview($parameters)
    {
        $parameters = json_decode(decrypt($parameters), true);
//        dd($parameters);
        return view('reports.claims.preview', compact('parameters'));
    }

    public function exportClaimReportPdf($parameters)
    {
        $parameters = json_decode(decrypt($parameters), true);
        $records = $this->getRecords($parameters);

        if ($parameters['duration'] == 'yearly') {
            if ($parameters['year'] == 'all') {
                $fileName = 'claim_report.pdf';
                $title = 'All Claim reports';
            } else {
                if ($parameters['status'] != 'both') {
                    $fileName = 'claim_report.pdf';
                    $title = $parameters['status'] . ' claim reports from ' . $parameters['dates']['from'] . ' to ' . $parameters['dates']['to'] . '';
                } else {
                    $fileName = 'claim_report.pdf';
                    $title = 'All claim reports from ' . $parameters['dates']['from'] . ' to ' . $parameters['dates']['to'] . '';
                }
            }
        } else {
            if ($parameters['status'] != 'both') {
                $fileName = 'claim_report.pdf';
                $title = $parameters['status'] . ' claim reports from ' . $parameters['from'] . ' to ' . $parameters['to'] . '';
            } else {
                $fileName = 'claim_report.pdf';
                $title = 'All Claim reports from ' . $parameters['from'] . ' to ' . $parameters['to'] . '';
            }
        }
        $records = $records->get();
        $pdf = PDF::loadView('exports.claims.reports.pdf.claim', compact('records', 'title', 'parameters'));
        $pdf->setPaper('a4', 'landscape');
        $pdf->setOption(['dpi' => 150, 'defaultFont' => 'sans-serif']);
        return $pdf->download($fileName);
    }
}
