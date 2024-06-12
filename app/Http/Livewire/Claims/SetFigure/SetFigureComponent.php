<?php

namespace App\Http\Livewire\Claims\SetFigure;

use App\Models\Claims\TaxClaim;
use Livewire\Component;
use Illuminate\Support\Facades\Storage;

class SetFigureComponent extends Component
{
    public $taxClaim;
    public $originalAmount, $agreedAmount;
    public $supportingDocument;

    protected $rules = [
        'agreedAmount' => ['required', 'numeric'],
        'supportingDocument' => ['nullable', 'file', 'max:255'],
    ];

    /**
     * Mount the component with the specified tax claim ID.
     *
     * @param  int  $taxClaimId  The ID of the tax claim
     * @return void
     */
    public function mount($taxClaimId)
    {
        $this->taxClaim = TaxClaim::findOrFail($taxClaimId);
        $this->agreedAmount = $this->taxClaim->amount;
        $this->originalAmount = $this->taxClaim->amount;
    }

    /**
     * Save the agreed amount for the tax claim.
     *
     * This method removes thousand separators from the agreed amount, validates the input data, and updates the tax claim with the agreed amount and supporting document.
     *
     * @return void
     */


    public function saveAgreedAmount()
    {
        // Remove thousand separators from agreedAmount
        $this->agreedAmount = str_replace(',', '', $this->agreedAmount);

        $this->validate();

        if (empty($this->taxClaim->original_figure)) {
            $this->taxClaim->original_figure = $this->originalAmount;
        }

        $this->taxClaim->amount = $this->agreedAmount;

        if ($this->supportingDocument) {
            $path = $this->supportingDocument->store('supporting_documents');
            $this->taxClaim->supporting_document_for_agreed_figure = $path;
        }

        $this->taxClaim->save();

        // Emit an event with the updated agreed amount
        $this->emit('agreedAmountUpdated', $this->agreedAmount);
    }

    /**
     * Enable editing of the agreed amount and supporting document for the tax claim.
     *
     * This method retrieves the original agreed amount and supporting document from the tax claim and sets them as the current values for editing.
     *
     * @return void
     */
    public function enableEditing()
    {
        $this->agreedAmount = $this->taxClaim->original_figure;
        $this->supportingDocument = $this->taxClaim->supporting_document_for_agreed_figure;
    }


    public function render()
    {
        return view('livewire.claims.set-figure.set-figure-component');
    }
}
