<?php

namespace App\Http\Controllers;

use App\Models\ZmBill;
use Endroid\QrCode\Color\Color;
use Illuminate\Http\Request;
use Endroid\QrCode\Builder\Builder;
use Endroid\QrCode\Encoding\Encoding;
use Endroid\QrCode\Label\Alignment\LabelAlignmentCenter;
use Endroid\QrCode\Writer\PngWriter;
use Endroid\QrCode\ErrorCorrectionLevel\ErrorCorrectionLevelHigh;
use Endroid\QrCode\Writer\SvgWriter;
use PDF;

class QRCodeGeneratorController extends Controller
{
    public function invoice($id, Request $request)
    {
        $bill = ZmBill::with('user')->find(decrypt($id));
        $name = $bill->user->full_name ?? '';

        $code = '{"opType":"2","shortCode":"001001","billReference":"' . $bill->control_number . '","amount":"' .
            $bill->amount . '","billCcy":'.$bill->currency.',"billExprDt":"' . $bill->expire . '","billPayOpt":"1",
            "billRsv01":"ZANZIBAR REVENUE BOARD|' . $name . '"}';

        $result = Builder::create()
            ->writer(new PngWriter())
            ->writerOptions([SvgWriter::WRITER_OPTION_EXCLUDE_XML_DECLARATION => true])
            ->data($code)
            ->encoding(new Encoding('UTF-8'))
            ->errorCorrectionLevel(new ErrorCorrectionLevelHigh())
            ->size(300)
            ->margin(10)
            ->logoPath(public_path('/images/logo.png'))
            ->logoResizeToHeight(64)
            ->logoResizeToWidth(64)
            ->labelText('SCAN AND PAY')
            ->labelAlignment(new LabelAlignmentCenter())
            ->build();

        header('Content-Type: ' . $result->getMimeType());

        $dataUri = $result->getDataUri();

        // return view('zanMalipo.pdf.invoice', compact('dataUri', 'bill'));
        $pdf = PDF::loadView('zanMalipo.pdf.invoice', compact('dataUri', 'bill'));



        return $pdf->download('ZanMalipo_invoice_' . time() . '.pdf');
    }

    public function transfer($id)
    {
        $bill = ZmBill::find($id);
        $name = $bill->user->first_name . ' ' . $bill->user->last_name;
        $code = '{"opType":"'.$bill->payment_option.'","shortCode":"001001","billReference":"' . $bill->control_number . '","amount":"' .
            $bill->amount . '","billCcy":'.$bill->currency.',"billExprDt":"' . $bill->expire . '","billPayOpt":"1",
            "billRsv01":"ZANZIBAR PORTS CORPORATION|' . $name . '"}';

        $result = Builder::create()
            ->writer(new PngWriter())
            ->writerOptions([SvgWriter::WRITER_OPTION_EXCLUDE_XML_DECLARATION => true])
            ->data($code)
            ->encoding(new Encoding('UTF-8'))
            ->errorCorrectionLevel(new ErrorCorrectionLevelHigh())
            ->size(200)
            ->margin(10)
            ->logoPath(public_path('/images/logo.png'))
            ->logoResizeToHeight(64)
            ->logoResizeToWidth(64)
            ->labelText('SCAN AND PAY')
            ->labelAlignment(new LabelAlignmentCenter())
            ->build();

        header('Content-Type: ' . $result->getMimeType());

        $dataUri = $result->getDataUri();
        $pdf = PDF::loadView('zanMalipo.pdf.transfer', compact('dataUri', 'bill'));

        return $pdf->stream('ZanMalipo_transfer_' . time() . '.pdf');
    }

    public function receipt($id)
    {
        $bill = ZmBill::find(decrypt($id));
        $pdf = PDF::loadView('zanMalipo.pdf.receipt', compact('bill'));
        return $pdf->download('ZanMalipo_receipt_' . time() . '.pdf');
    }
}
