<?php

namespace App\Mail\TaxClearance;

use App\Models\SystemSetting;
use App\Models\TaxClearanceRequest;
use Endroid\QrCode\Builder\Builder;
use Endroid\QrCode\Encoding\Encoding;
use Endroid\QrCode\ErrorCorrectionLevel\ErrorCorrectionLevelHigh;
use Endroid\QrCode\Label\Alignment\LabelAlignmentCenter;
use Endroid\QrCode\Writer\PngWriter;
use Endroid\QrCode\Writer\SvgWriter;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;
use PDF;

class TaxClearanceApproved extends Mailable
{
    use Queueable, SerializesModels;

    public $payload;

    /**
     * Create a new message instance.
     *
     * @return void
     */
    public function __construct($payload)
    {
        $this->payload = $payload;
    }

    /**
     * Build the message.
     *
     * @return $this
     */
    public function build()
    {
        $taxClearanceRequest = $this->payload[1];

        $taxClearanceRequest = TaxClearanceRequest::findOrFail($taxClearanceRequest->id, ['id', 'business_id', 'business_location_id', 'reason', 'marking', 'approved_on', 'expire_on', 'status', 'deleted_at', 'created_at', 'updated_at', 'certificate_number']);

        $location = $taxClearanceRequest->businessLocation;

        $url = config('modulesconfig.taxpayer_url') . route('qrcode-check.tax-clearance.certificate', ['clearanceId' => base64_encode($taxClearanceRequest->id)], 0);

        $result = Builder::create()
            ->writer(new PngWriter())
            ->writerOptions([SvgWriter::WRITER_OPTION_EXCLUDE_XML_DECLARATION => false])
            ->data($url)
            ->encoding(new Encoding('UTF-8'))
            ->errorCorrectionLevel(new ErrorCorrectionLevelHigh())
            ->size(207)
            ->margin(0)
            ->logoPath(public_path('/images/logo.png'))
            ->logoResizeToHeight(36)
            ->logoResizeToWidth(36)
            ->labelText('')
            ->labelAlignment(new LabelAlignmentCenter())
            ->build();

        header('Content-Type: ' . $result->getMimeType());
        $dataUri = $result->getDataUri();

        $signature = getSignature($taxClearanceRequest);

        if (!$signature) {
            throw new \Exception('Signature not found');
        }

        $signaturePath = $signature->image;
        $commissinerFullName = $signature->name;
        $title = $signature->title;

        $pdf = PDF::loadView('tax-clearance.includes.online-certificate', compact('dataUri', 'location', 'taxClearanceRequest', 'signaturePath', 'commissinerFullName', 'title'));
        $pdf->setPaper('a4', 'portrait');
        $pdf->setOption(['dpi' => 150, 'defaultFont' => 'sans-serif', 'isRemoteEnabled' => true]);

        return $this->markdown('emails.taxclearance.taxclearanceapproved')->attachData($pdf->output(), "tax_clearance_certificate.pdf")->subject('Tax Clearance Application');
    }
}
