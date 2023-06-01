<?php

namespace App\Http\Controllers;

use App\Models\ZmBill;
use App\Models\ZrbBankAccount;
use Carbon\Carbon;
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
        $id = decrypt($id);
        $bill = ZmBill::with('user')->findOrFail($id);
        $name = $bill->user->full_name ?? '';
        
        $url = env('TAXPAYER_URL') . route('qrcode-check.invoice',  base64_encode(strval($id)), 0);

        $qrPayload = [
            'opType' => '2',
            'shortCode' => '100001',
            'amount' => $bill->amount,
            'billCcy' => $bill->currency,
            'billExprDt' => Carbon::parse($bill->expire_date)->toDateString(),
            'billPayOpt' => (int) $bill->payment_option,
            'billRsv01' => $bill->description
        ];

        $result = Builder::create()
        ->writer(new PngWriter())
        ->writerOptions([SvgWriter::WRITER_OPTION_EXCLUDE_XML_DECLARATION => true])
        ->data(json_encode($qrPayload))
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
    
    public function transfer($billId, $bankAccountId)
    {
        $billId = decrypt($billId);
        $bill = ZmBill::findOrFail($billId);
        $bankAccount = ZrbBankAccount::findOrFail(decrypt($bankAccountId));
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
        $pdf = PDF::loadView('zanMalipo.pdf.transfer', compact('dataUri', 'bill', 'bankAccount'));
        return $pdf->stream('ZanMalipo_transfer_' . time() . '.pdf');
    }

    public function receipt($id)
    {
        $bill = ZmBill::findOrFail(decrypt($id));
        $pdf = PDF::loadView('zanMalipo.pdf.receipt', compact('bill'));
        // return $pdf;
        return $pdf->download('ZanMalipo_receipt_' . time() . '.pdf');
    }
}
