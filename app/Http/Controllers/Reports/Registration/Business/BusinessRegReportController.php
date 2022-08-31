<?php

namespace App\Http\Controllers\Reports\Registration\Business;

use App\Http\Controllers\Controller;
use App\Models\TaxType;
use App\Traits\RegistrationReportTrait;
use Illuminate\Http\Request;
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

    public function byNature($isicId,$level){
        $isicId = decrypt($isicId);
        $level = decrypt($level);
        // dd($level,$isicId);
        return view('reports.registration.business-by-nature',compact('isicId','level'));
    }

    public function exportBusinessByNatureReportPdf($isic1Id)
    {
        $isic1Id = decrypt($isic1Id);
        $title = 'Business By Nature';
        $records = $this->businessByNatureQuery($isic1Id)->get();
        $pdf = PDF::loadView('exports.registration.pdf.business-by-nature', compact('records', 'title'));
        $pdf->setPaper('a4', 'portrait');
        $pdf->setOption(['dpi' => 150, 'defaultFont' => 'sans-serif']);
        return $pdf->download('business-by-nature.pdf');
    }

    public function byTaxType($tax_type_id){
        $tax_type_id = decrypt($tax_type_id);
        $taxType = TaxType::find($tax_type_id);
        return view('reports.registration.business-by-tax-type',compact('taxType'));
    }

    public function exportBusinessByTaxTypeReportPdf($tax_type_id)
    {
        $tax_type_id = decrypt($tax_type_id);
        $title = 'Business By Tax Type';
        $records = $this->businessByTaxTypeQuery($tax_type_id)->get();
        $taxType = TaxType::find($tax_type_id);
        $pdf = PDF::loadView('exports.registration.pdf.business-by-tax-type', compact('records', 'title','taxType'));
        $pdf->setPaper('a4', 'portrait');
        $pdf->setOption(['dpi' => 150, 'defaultFont' => 'sans-serif']);
        return $pdf->download('business by tax-type.pdf');
    }

    public function byTurnOverLast($from,$to){
        return view('reports.registration.business-by-pre-turn-over',compact('from','to'));
    }

    public function exportBusinessByTurnOverLastReportPdf($from,$to)
    {
        $title = 'Business By Turn Over';
        $records = $this->businessByTurnOverLastQuery($from,$to)->get();
        $pdf = PDF::loadView('exports.registration.pdf.business-by-pre-turn-over', compact('records', 'title'));
        $pdf->setPaper('a4', 'portrait');
        $pdf->setOption(['dpi' => 150, 'defaultFont' => 'sans-serif']);
        return $pdf->download('business by pre-turn-over.pdf');
    }

    public function byTurnOverNext($from,$to){
        return view('reports.registration.business-by-post-turn-over',compact('from','to'));
    }

    public function exportBusinessByTurnOverNextReportPdf($from,$to)
    {
        $title = 'Business By Turn Over';
        $records = $this->businessByTurnOverNextQuery($from,$to)->get();
        $pdf = PDF::loadView('exports.registration.pdf.business-by-post-turn-over', compact('records', 'title'));
        $pdf->setPaper('a4', 'portrait');
        $pdf->setOption(['dpi' => 150, 'defaultFont' => 'sans-serif']);
        return $pdf->download('business by post-turn-over.pdf');
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
