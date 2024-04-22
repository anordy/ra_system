<?php

namespace App\Http\Livewire\Claims\SetFigure;

use App\Models\Claims\TaxClaim;
use Livewire\Component;

class SetFigureComponent extends Component
{
    public $taxClaim;
    public $originalAmount,$agreedAmount;
    public $supportingDocument;

    protected $rules = [
        'agreedAmount' => ['required', 'numeric'],
        'supportingDocument' => ['nullable', 'file', 'max:255'],
    ];

    public function mount($taxClaimId)
    {
        $this->taxClaim = TaxClaim::findOrFail($taxClaimId);
        $this->agreedAmount = $this->taxClaim->amount;
        $this->originalAmount = $this->taxClaim->amount;
    }

    public function saveAgreedAmount()
    {
        
        // Remove thousand separators from agreedAmount
        $this->agreedAmount = str_replace(',', '', $this->agreedAmount);
        
        $this->validate();
        $this->taxClaim->original_figure = $this->originalAmount;
        $this->taxClaim->amount = $this->agreedAmount;
        $this->taxClaim->supporting_document_for_agreed_figure = $this->supportingDocument;
        $this->taxClaim->save();
    }

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
