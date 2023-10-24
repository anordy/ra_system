<?php

namespace App\Http\Livewire\PropertyTax;


use App\Enum\PropertyTypeStatus;
use App\Models\PropertyTax\Property;
use Livewire\Component;

class BillPreview extends Component
{
    public $hotelBill;
    public $condominiumBill;
    public $property;
    public function mount($propertyId)
    {
        $this->property = Property::findOrFail(decrypt($propertyId));

        if ($this->property->type === PropertyTypeStatus::HOTEL) {
            $this->hotelBill = $this->property->star;
        } else if ($this->property->type === PropertyTypeStatus::CONDOMINIUM) {
            // TODO: Fetch from System settings
            $this->condominiumBill = 10000;
        } else if ($this->property->type === PropertyTypeStatus::STOREY_BUSINESS || $this->property->type === PropertyTypeStatus::RESIDENTIAL_STOREY) {

        } else if ($this->property->type === PropertyTypeStatus::OTHER) {

        } else {
            abort(404);
        }
    }

    public function render()
    {
        return view('livewire.property-tax.bill-preview');
    }
}
