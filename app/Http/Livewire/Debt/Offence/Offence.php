<?php

namespace App\Http\Livewire\Debt\Offence;

use App\Enum\ReturnCategory;
use App\Models\Returns\TaxReturn;
use App\Models\ZmBill;
use Carbon\Carbon;
use Illuminate\Database\Eloquent\Builder;
use Livewire\Component;
use Rappasoft\LaravelLivewireTables\Views\Column;
use App\Models\Offence\Offence as OffenceModal;

class Offence extends Component
{
//    public $offences = [];
//
//    public function mount($offences)
//    {
//        $this->$offences = $offences->toArray() ?? [];
//    }

    public function render()
    {
        $offences = OffenceModal::with('taxTypes')->orderBy('id','desc')->get();

        return view('livewire.offence.offence',compact('offences'));
    }

}
