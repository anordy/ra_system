<?php

namespace App\Http\Controllers\Reports\Claims;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class ClaimReportController extends Controller
{

    public function init(){
        return view('reports.claims.index');
    }

    public function preview($parameters)
    {
        return view('reports.claims.preview',compact('parameters'));
    }

    public function exportBusinessesReportPdf($data)
    {
        $parameters = json_decode(decrypt($data),true);
        $records = $this->getBusinessBuilder($parameters)->get();

        $pdf = PDF::loadView('exports.claims.pdf.business',compact('records'));
        $pdf->setPaper('a4', 'landscape');
        $pdf->setOption(['dpi' => 150, 'defaultFont' => 'sans-serif']);
        return $pdf->download('claims.pdf');
    }
}
