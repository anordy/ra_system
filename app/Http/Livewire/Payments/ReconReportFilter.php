<?php

namespace App\Http\Livewire\Payments;

use App\Exports\ReconReportExport;
use App\Traits\ReconReportTrait;
use Carbon\Carbon;
use Jantinnerezo\LivewireAlert\LivewireAlert;
use Livewire\Component;
use Maatwebsite\Excel\Facades\Excel;

class ReconReportFilter extends Component
{
    use ReconReportTrait;
    use LivewireAlert;

    public $range_start;
    public $range_end;
    public $today;
    public $hasData=false;
    public $parameters =[];

    public function mount(){
        $this->today = date('Y-m-d');
    }

    protected function rules(){
        return [
            'range_start' => 'required|strip_tag',
            'range_end' =>   'required|strip_tag',
        ];
    }

    public function search(){
        $this->validate();
        $this->parameters['range_start'] =Carbon::parse($this->range_start)->startOfDay()->toDatetimeString(); 
        $this->parameters['range_end'] = Carbon::parse($this->range_end)->endOfDay()->toDatetimeString();

        if($this->getBillBuilder($this->parameters)->count() < 1){
            $this->hasData = false;
            $this->alert('error','No record found');            
        }else{
            $this->hasData = true;
        }

    }

    public function exportExcel(){
        $this->validate();
        $records = $this->getBillBuilder($this->parameters)->get();

        $fileName = 'recon-report-'.time().'.xlsx';
        $title    = 'For bills created between '.$this->parameters['range_start'].' and '.$this->parameters['range_end'];
        
        $this->alert('success', 'Exporting Excel File');
        return Excel::download(new ReconReportExport($records, $title), $fileName);          
    }

    public function render()
    {
        return view('livewire.payments.recon-report-filter');
    }
}
