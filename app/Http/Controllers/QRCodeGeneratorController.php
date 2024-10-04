<?php

namespace App\Http\Controllers;

use PDF;
use Carbon\Carbon;
use App\Models\ZmBill;
use App\Models\BusinessBank;
use Illuminate\Http\Request;
use App\Models\ZrbBankAccount;
use Endroid\QrCode\Color\Color;
use Endroid\QrCode\Builder\Builder;
use Endroid\QrCode\Writer\PngWriter;
use Endroid\QrCode\Writer\SvgWriter;
use Endroid\QrCode\Encoding\Encoding;
use Endroid\QrCode\Label\Alignment\LabelAlignmentCenter;
use Endroid\QrCode\ErrorCorrectionLevel\ErrorCorrectionLevelHigh;

class QRCodeGeneratorController extends Controller
{
    public function invoice($id)
    {
        $url = env('TAXPAYER_URL') . route('qrcode-check.invoice', base64_encode(strval(decrypt($id))));

        $result = Builder::create()
            ->writer(new PngWriter())
            ->writerOptions([SvgWriter::WRITER_OPTION_EXCLUDE_XML_DECLARATION => true])
            ->data($url)
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
        
        $pdf = PDF::loadView('zanMalipo.pdf.invoice', compact('dataUri', 'bill'));

        return $pdf->download('ZanMalipo_invoice_' . time() . '.pdf');
    }
    
    public function transfer($billId, $bankAccountId, $businessBankAccId)
    {
        $billId = decrypt($billId);
        $bill = ZmBill::findOrFail($billId);
        $bankAccount = ZrbBankAccount::findOrFail(decrypt($bankAccountId));
        $businessBank = BusinessBank::find(decrypt($businessBankAccId));

        $name = $bill->payer_name;

        $url = env('TAXPAYER_URL') . route('qrcode-check.transfer',  base64_encode(strval($billId)), 0);

        $result = Builder::create()
            ->writer(new PngWriter())
            ->writerOptions([SvgWriter::WRITER_OPTION_EXCLUDE_XML_DECLARATION => true])
            ->data($url)
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
        $pdf = PDF::loadView('zanMalipo.pdf.transfer', compact('dataUri', 'bill', 'bankAccount', 'businessBank'));
        return $pdf->stream('ZanMalipo_transfer_' . time() . '.pdf');
    }

    public function receipt($id)
    {
        $bill = ZmBill::find(decrypt($id));

        $url = env('TAXPAYER_URL') . route('qrcode-check.invoice', base64_encode(strval(decrypt($id))));

        $result = Builder::create()
            ->writer(new PngWriter())
            ->writerOptions([SvgWriter::WRITER_OPTION_EXCLUDE_XML_DECLARATION => true])
            ->data($url)
            ->encoding(new Encoding('UTF-8'))
            ->errorCorrectionLevel(new ErrorCorrectionLevelHigh())
            ->size(300)
            ->margin(10)
            ->logoPath(public_path('/images/logo.png'))
            ->logoResizeToHeight(64)
            ->logoResizeToWidth(64)
            ->labelText('')
            ->labelAlignment(new LabelAlignmentCenter())
            ->build();

        header('Content-Type: ' . $result->getMimeType());

        $dataUri = $result->getDataUri();
        $pdf = PDF::loadView('zanMalipo.pdf.receipt', compact('bill', 'dataUri'));
        return $pdf->download('ZanMalipo_receipt_' . time() . '.pdf');
    }

}
