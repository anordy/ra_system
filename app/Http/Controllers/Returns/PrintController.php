<?php

namespace App\Http\Controllers\Returns;

use App\Http\Controllers\Controller;
use App\Models\Returns\StampDuty\StampDutyReturn;
use App\Models\Returns\TaxReturn;
use App\Models\TaxType;
use Barryvdh\DomPDF\Facade\Pdf;
use Illuminate\Http\Request;

class PrintController extends Controller
{
    public function print($taxReturnId){
        $taxReturn = TaxReturn::findOrFail(decrypt($taxReturnId));
        switch ($taxReturn->taxType->code){
            case TaxType::STAMP_DUTY:
                $returnView = 'print.returns.includes.stamp-duty';
                break;
            case TaxType::ELECTRONIC_MONEY_TRANSACTION:
                $returnView = 'print.returns.includes.em-transaction';
                break;
            default:
                return;
        }

        $pdf = PDF::loadView('print.returns.index', ['return' => $taxReturn->return, 'returnView' => $returnView]);
        $pdf->setPaper('a4', 'portrait');
        $pdf->setOption(['dpi' => 150, 'defaultFont' => 'sans-serif']);
        return $pdf->stream();
    }
}
