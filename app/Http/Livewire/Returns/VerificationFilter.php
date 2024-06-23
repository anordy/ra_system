<?php

namespace App\Http\Livewire\Returns;

use Livewire\Component;

class VerificationFilter extends Component
{
    public $penaltyTable;
    public $returnTable;
    public $optionYears;
    public $tableName;
    public $month;
    public $year;
    public $from;
    public $to;
    public $period;

    public function mount($tablename)
    {
        //set current year at first
        $this->year      = date('Y');
        $this->month     = strval(intval(date('m')));
        $this->period    = 'Monthly';
        $this->tableName = $tablename;
        //get options for years
        $optionStartYear   = 2020;
        $this->optionYears = range($optionStartYear, date('Y'));

        //add All to year options
        $this->optionYears[] = 'All';
        //sort array
        rsort($this->optionYears);
        //add Range to year options
        $this->optionYears[] = 'Custom Range';
    }
    
    public function filter()
    {
        $filters = [
            'year' => $this->year,
            'month'=> $this->month,
            'from' => $this->from,
            'to'   => $this->to,
        ];

        $this->emitTo($this->tableName, 'filterData', $filters);
    }

    public function render()
    {
        return view('livewire.returns.verification-filter');
    }
}
