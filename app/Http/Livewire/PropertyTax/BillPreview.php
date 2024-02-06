<?php

namespace App\Http\Livewire\PropertyTax;


use App\Enum\PropertyTypeStatus;
use App\Models\PropertyTax\Property;
use App\Traits\PropertyTaxTrait;
use Livewire\Component;

class BillPreview extends Component
{
    use PropertyTaxTrait;

    public $breakDown;
    public $property;
    public function mount($propertyId)
    {
        $this->property = Property::findOrFail(decrypt($propertyId));
        $this->breakDown = $this->previewPayableAmount($this->property);
    }

    public function render()
    {
        return view('livewire.property-tax.bill-preview');
    }
}
