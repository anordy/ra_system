<?php

namespace App\Http\Livewire\Debt;

use Livewire\Component;
use Jantinnerezo\LivewireAlert\LivewireAlert;

class DebtPenalties extends Component
{

    use LivewireAlert;

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
