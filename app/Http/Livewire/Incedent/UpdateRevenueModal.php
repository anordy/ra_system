<?php

namespace App\Http\Livewire\Incedent;

use App\Models\Currency;
use App\Traits\CustomAlert;
use Illuminate\Validation\Rule;
use Livewire\Component;
use Livewire\WithFileUploads;

class UpdateRevenueModal extends Component
{

    use WithFileUploads,CustomAlert;
    public $revenuDetected,$revenuePrevented,$revenueRecovered,$currency,$type,
           $overchargingDetected,$overchargingPrevented,$overchargingRecovered;
           public $currencies = [];

    public function mount($incedentId)
    {
        $this->currencies = Currency::query()
        ->select('id','name','code')->get();
    }



    public function submit()
    {
      
    }

    public function render()
    {
        return view('livewire.incedent.update-revenue-modal');
    }
}
