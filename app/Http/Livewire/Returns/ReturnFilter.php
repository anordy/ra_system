<?php

namespace App\Http\Livewire\Returns;

use Livewire\Component;

class ReturnFilter extends Component
{
    public $payment_type;
    public $year;
    public $month;
    public $optionYears;

    public function mount()
    {
        //set current year at first
        $this->year   = date('Y');
        $this->period = 'Monthly';
        $this->month  = strval(intval(date('m')));

        //get options for years
        $optionStartYear   = 2020;
        $this->optionYears = range($optionStartYear, date('Y'));

        //add All to year options
        $this->optionYears[] = 'All';
        //sort array
        rsort($this->optionYears);
    }
    
    public function fillter()
    {
        $filters = [
            'type' => $this->payment_type,
            'year' => $this->year,
            'month'=> $this->month,
        ];

        $this->emitTo('returns.lump-sum.lump-sum-returns-table', 'filterData', $filters);
    }

    public function render()
    {
        return view('livewire.returns.return-filter ');
    }
}
