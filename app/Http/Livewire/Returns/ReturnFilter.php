<?php

namespace App\Http\Livewire\Returns;

use Livewire\Component;

class ReturnFilter extends Component
{
    public $payment_type;
    public $penaltyTable;
    public $returnTable;
    public $optionYears;
    public $tableName;
    public $cardOne;
    public $cardTwo;
    public $month;
    public $year;
    public $from;
    public $to;

    public function mount($tablename, $cardOne, $cardTwo)
    {
        //set current year at first
        $this->year      = date('Y');
        $this->month     = strval(intval(date('m')));
        $this->period    = 'Monthly';
        $this->cardOne   = $cardOne;
        $this->cardTwo   = $cardTwo;
        $this->tableName = $tablename;
        //get options for years
        $optionStartYear   = 2020;
        $this->optionYears = range($optionStartYear, date('Y'));

        //add All & Range to year options
        $this->optionYears[] = 'All';
        $this->optionYears[] = 'Custom Range';
        //sort array
        rsort($this->optionYears);
    }
    
    public function fillter()
    {
        $filters = [
            'type' => $this->payment_type,
            'year' => $this->year,
            'month'=> $this->month,
            'from' => $this->from,
            'to'   => $this->to,
        ];

        $this->emitTo($this->tableName, 'filterData', $filters);
        $this->emitTo($this->cardOne, 'filterData', $filters);
        $this->emitTo($this->cardTwo, 'filterData', $filters);
    }

    public function render()
    {
        return view('livewire.returns.return-filter ');
    }
}
