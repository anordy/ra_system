<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use PDF;

class AllPdfController extends Controller
{
    public function index()
    {
        return view('layouts.all-pdf.index');
    }

    public function demandNotice($file)
    {
        $file;

        switch ($file) {
            case 'demand-notice':
                $pdf = PDF::loadView('layouts.all-pdf.demand-notice');

                break;

            case 'third-party-payment':
                $pdf = PDF::loadView('layouts.all-pdf.third-party-payment');
              
                break;
                
            case 'distress-warant':
                $pdf = PDF::loadView('layouts.all-pdf.distress-warant');

                break;
            case 'distress-notice':
                $pdf = PDF::loadView('layouts.all-pdf.distress-notice');

                break;
            case 'goods-invetory':
                $pdf = PDF::loadView('layouts.all-pdf.goods-invetory');

                break;
            case 'goods-schedule':
                $pdf = PDF::loadView('layouts.all-pdf.goods-schedule');

                break;
            case 'payment-installments':
                $pdf = PDF::loadView('layouts.all-pdf.payment-installments');

                break;
            case 'distrained-goods':
                $pdf = PDF::loadView('layouts.all-pdf.distrained-goods');

                break;
            case 'penalty-remission':
                $pdf = PDF::loadView('layouts.all-pdf.penalty-remission');

                break;
            case 'uncollectable-tax':
                $pdf = PDF::loadView('layouts.all-pdf.uncollectable-tax');

                break;
            case 'outstanding-tax':
                $pdf = PDF::loadView('layouts.all-pdf.outstanding-tax');

                break;

            default:
                $pdf = PDF::loadView('layouts.all-pdf.index');
        }

        $pdf->setPaper('a4', 'portrait');
        $pdf->setOption(['dpi' => 150, 'defaultFont' => 'sans-serif']);
        
        return $pdf->stream();
    }
}
