<?php

namespace App\Http\Livewire\Settings\CertificateSignature;

use App\Enum\TaxClearanceStatus;
use App\Models\BusinessLocation;
use App\Models\BusinessStatus;
use App\Models\BusinessTaxType;
use App\Models\CertificateSignature;
use App\Models\TaxClearanceRequest;
use App\Traits\CustomAlert;
use Livewire\Component;

class CertificateSignaturePreviewModal extends Component
{

    use CustomAlert;

    public $location, $taxClearance, $taxType;


    public function mount($id)
    {
        $signature = CertificateSignature::findOrFail(decrypt($id), ['start_date', 'end_date']);

        $startDate = $signature->start_date;
        $endDate = $signature->end_date;

        $this->location = BusinessLocation::select('id', 'business_id')
            ->where('status', BusinessStatus::APPROVED)
            ->whereBetween('approved_on', [$startDate, $endDate])
            ->firstOrFail();

        $this->taxClearance = TaxClearanceRequest::select('id')
            ->where('status', TaxClearanceStatus::APPROVED)
            ->whereBetween('approved_on', [$startDate, $endDate])
            ->firstOrFail();

        $this->taxType = BusinessTaxType::select('tax_type_id')->where('business_id', $this->location->business_id)
            ->firstOrFail();

    }



    public function render()
    {
        return view('livewire.settings.certificate-signature.certificate-signature-preview-modal');
    }
}
