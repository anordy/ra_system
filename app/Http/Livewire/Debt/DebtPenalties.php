<?php

namespace App\Http\Livewire\Debt;

use Livewire\Component;
use App\Traits\CustomAlert;

class DebtPenalties extends Component
{

    use CustomAlert;

    public $penalties = [];

    public function mount($penalties)
    {
        $this->penalties = $penalties->toArray() ?? [];
    }

    public function render()
    {
        return view('livewire.debts.debt-penalties');
    }
}
