<?php

namespace App\Mail\Business\Taxtype;

use PDF;
use App\Models\TaxType;
use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use App\Models\BusinessLocation;
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
        $business_locations = $this->payload['business']->locations;
        $new_taxes = $this->payload['new_taxes'] ?? [];

        $email = $this->markdown('emails.business.taxtypes.change')->subject("ZRB Change Tax Type Request - " . strtoupper($this->payload['business']->name));


        if(!empty($new_taxes)) {
            foreach ($new_taxes as $taxType) {
                // $attachments is an array with file paths of attachments
                if (!empty($business_locations)) {
                    foreach ($business_locations as $location) {
    
                        if ($location->status == 'approved') {
                            $tax = TaxType::find($taxType['new_tax_id']);
        
                            $code = 'ZIN: ' . $location->zin . ", " .
                                'Business Name: ' . $location->business->name . ", " .
                                'Tax Type: ' . $tax->name . ", " .
                                'Location: ' . "{$location->street}, {$location->district->name}, {$location->region->name}" . ", " .
                                'Website: ' . 'https://uat.ubx.co.tz:8888/zrb_client/public/login';
                    
                            $result = Builder::create()
                                ->writer(new PngWriter())
                                ->writerOptions([SvgWriter::WRITER_OPTION_EXCLUDE_XML_DECLARATION => false])
                                ->data($code)
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
            
                            $pdf = PDF::loadView('business.certificate', compact('location', 'tax', 'dataUri'));
                            $pdf->setPaper('a4', 'portrait');
                            $pdf->setOption(['dpi' => 150, 'defaultFont' => 'sans-serif']);
            
                            $email->attachData($pdf->output(), "{$this->payload['business']->name}_{$tax->name}_certificate.pdf");
                        }
        
                    }
                return $email;

                }
               
            }
        }
    
    }
}
