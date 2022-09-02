<?php

namespace App\Http\Controllers\Reports\Business;

use App\Http\Controllers\Controller;
use App\Traits\RegistrationReportTrait;
use PDF;

class BusinessRegReportController extends Controller
{
    use RegistrationReportTrait;

    public function init(){
        return view('reports.business.init');
    }

    public function preview($parameters)
    {
        return view('reports.business.preview',compact('parameters')); 
    }

    public function exportBusinessesReportPdf($data)
    {
        $parameters = json_decode(decrypt($data),true);
        $records = $this->getBusinessBuilder($parameters)->get();

        $pdf = PDF::loadView('exports.business.pdf.business',compact('records'));
        $pdf->setPaper('a4', 'portrait');
        $pdf->setOption(['dpi' => 150, 'defaultFont' => 'sans-serif']);
        return $pdf->download('Business.pdf');
    }

}
