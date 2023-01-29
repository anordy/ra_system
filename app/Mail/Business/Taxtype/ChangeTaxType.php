<?php

namespace App\Mail\Business\Taxtype;

use PDF;
use App\Models\TaxType;
use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use App\Models\BusinessTaxType;
use Endroid\QrCode\Builder\Builder;
use Endroid\QrCode\Writer\PngWriter;
use Endroid\QrCode\Writer\SvgWriter;
use Endroid\QrCode\Encoding\Encoding;
use Illuminate\Queue\SerializesModels;
use Illuminate\Contracts\Queue\ShouldQueue;
use Endroid\QrCode\Label\Alignment\LabelAlignmentCenter;
use Endroid\QrCode\ErrorCorrectionLevel\ErrorCorrectionLevelHigh;

class ChangeTaxType extends Mailable
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
        $business_locations = $this->payload['tax_change']->business->locations;

        $email = $this->markdown('emails.business.taxtypes.change')->subject("ZRB Change Tax Type Request - " . strtoupper($this->payload['tax_change']->business->name));

        // $attachments is an array with file paths of attachments
        if (!empty($business_locations)) {
            foreach ($business_locations as $location) {

                if ($location->status == 'approved') {
                    $taxTypeId = $this->payload['tax_change']->to_tax_type_id;
                    $tax = TaxType::find($taxTypeId);
                    if(is_null($tax)){
                        abort(404);
                    }
                    $taxType = BusinessTaxType::where('business_id', $location->business->id)->where('tax_type_id', $taxTypeId)->firstOrFail();

                    $certificateNumber = $this->generateCertificateNumber($location, $tax->prefix);

                    $code = 'ZIN: ' . $location->zin . ", " .
                    'Business Name: ' . $location->business->name . ", " .
                    'Tax Type: ' . $tax->name . ", " .
                    'Location: ' . "{$location->street->name}, {$location->district->name}, {$location->region->name}";

                    $result = Builder::create()
                        ->writer(new PngWriter())
                        ->writerOptions([SvgWriter::WRITER_OPTION_EXCLUDE_XML_DECLARATION => false])
                        ->data($code)
                        ->encoding(new Encoding('UTF-8'))
                        ->errorCorrectionLevel(new ErrorCorrectionLevelHigh())
                        ->size(207)
                        ->margin(0)
                        ->logoPath(public_path('/images/logo.jpg'))
                        ->logoResizeToHeight(36)
                        ->logoResizeToWidth(36)
                        ->labelText('')
                        ->labelAlignment(new LabelAlignmentCenter())
                        ->build();

                    header('Content-Type: ' . $result->getMimeType());

                    $dataUri = $result->getDataUri();

                    $pdf = PDF::loadView('business.tax-change-certificate', compact('location', 'tax', 'dataUri', 'taxType', 'certificateNumber'));

                    $pdf->setPaper('a4', 'portrait');
                    $pdf->setOption(['dpi' => 150, 'defaultFont' => 'sans-serif']);

                    $email->attachData($pdf->output(), "{$location->business->name}_{$location->name}_{$taxType->name}_certificate.pdf");
                }
            }
            return $email;
        }
    }

    public function generateCertificateNumber($location, $taxTypePrefix){
        $certificateNumber = $location->business->ztn_number;
        $taxRegionPrefix = $location->taxRegion->prefix;
        $ztn_location_number = $location->ztn_location_number;

        //If business is hotel and tax type is VAT change to Hotel VAT Prefix
        if ($location->business->business_type == 'hotel' && $taxTypePrefix == 'A') {
            $taxTypePrefix = 'B';
        }
        
        $certificateNumber = $certificateNumber.'-'.$taxRegionPrefix.$taxTypePrefix.$ztn_location_number;
        return $certificateNumber;
    }
}
