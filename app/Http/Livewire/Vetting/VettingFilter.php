<?php

namespace App\Http\Livewire\Vetting;

use App\Enum\ReportStatus;
use Livewire\Component;

class VettingFilter extends Component
{
    public $payment_type;
    public $returnTable;
    public $optionYears;
    public $tableName;
    public $cardTwo;
    public $month;
    public $year;
    public $from;
    public $to;

    public function mount($tablename)
    {
        //set current year at first
        $this->year = date('Y');
        $this->month = date('m');
        $this->period = 'Monthly';
        $this->tableName = $tablename;

        //get options for years
        $currentYear = date('Y');
        $this->optionYears = range($currentYear - 4, $currentYear); // Start year: current year minus 4

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
            'type' => $this->payment_type,
            'year' => $this->year,
            'month' => $this->month,
            'from' => $this->from,
            'to' => $this->to,
        ];
        $this->emitTo($this->tableName, 'filterData', $filters);
    }

    public function render()
    {
        return view('livewire.vetting.vetting-filter ');
    }
}
