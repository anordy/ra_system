<?php

namespace App\Http\Livewire\LandLease;

use App\Models\LandLease;
use DB;
use Livewire\Component;

class ReportTable extends Component
{
    public $query;
    public $pagination;
    public $startMonth;

    public function mount($query)
    {
        $this->query = $query;
        $this->pagination = 10;
    }
    public function render()
    {
        // $landLeases = DB::select($this->query);
        $landLeases = LandLease::where('created_at','<', '2022/08/01 23:59:59' )->get(); // todo: hardcoded values - alert
       
        return view('livewire.land-lease.report-table', compact('landLeases'));
    }

     //function to get number of days in a month
    public function getMonthDays($month)
    {
        $date = new \DateTime();
        $date->setDate(2020, $month, 1);
        $date->modify('last day of this month');
        return $date->format('d');
    }

    public function updated()
    {
        $date = new \DateTime();
        $date->setDate(2020, intval($this->startMonth), 1);
        $date->modify('last day of this month');
    }
}
