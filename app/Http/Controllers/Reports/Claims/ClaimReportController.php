<?php

namespace App\Http\Controllers\Reports\Claims;

use App\Enum\CustomMessage;
use App\Http\Controllers\Controller;
use App\Traits\ClaimReportTrait;
use Illuminate\Support\Facades\Gate;
use Illuminate\Support\Facades\Log;
use PDF;

class ClaimReportController extends Controller
{
    use ClaimReportTrait;

    const REPORT_TYPE = "All Claim reports ";

    public function init(){
        if (!Gate::allows('managerial-claim-report-view')) {
            abort(403);
        }
        return view('reports.claims.index');
    }

    public function preview($parameters)
    {
        if (!Gate::allows('managerial-claim-report-preview')) {
            abort(403);
        }

        try {
            $parameters = json_decode(decrypt($parameters), true);
            return view('reports.claims.preview', compact('parameters'));
        } catch (\Exception $exception){
            Log::error($exception);
            session()->flash('error', CustomMessage::error());
            return back();
        }
    }

    public function exportClaimReportPdf($parameters)
    {
        if (!Gate::allows('managerial-claim-report-pdf')) {
            abort(403);
        }

        try {
            $parameters = json_decode(decrypt($parameters), true);
            $records = $this->getRecords($parameters);

            if ($parameters['duration'] == 'yearly') {
                if ($parameters['status'] != 'both') {
                    $title = $parameters['status'] . ' claim reports from ' . $parameters['dates']['from'] . ' to ' . $parameters['dates']['to'] . '';
                } else {
                    $title = self::REPORT_TYPE . 'from ' . $parameters['dates']['from'] . ' to ' . $parameters['dates']['to'] . '';
                }
            } else {
                if ($parameters['status'] != 'both') {
                    $title = $parameters['status'] . ' claim reports from ' . $parameters['from'] . ' to ' . $parameters['to'] . '';
                } else {
                    $title = self::REPORT_TYPE . 'from ' . $parameters['from'] . ' to ' . $parameters['to'] . '';
                }
            }
            $records = $records->get();
            $pdf = PDF::loadView('exports.claims.reports.pdf.claim', compact('records', 'title', 'parameters'));
            $pdf->setPaper('a4', 'landscape');
            $pdf->setOption(['dpi' => 150, 'defaultFont' => 'sans-serif']);
            return $pdf->download('Claims Report.pdf');
        } catch (\Exception $exception){
            Log::error($exception);
            session()->flash('error', CustomMessage::error());
            return back();
        }
    }
}
