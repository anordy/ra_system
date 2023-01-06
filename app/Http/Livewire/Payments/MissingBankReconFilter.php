<?php

namespace App\Http\Livewire\Payments;

use App\Exports\BankReconExport;
use App\Models\MissingBankRecon;
use App\Traits\ReconReportTrait;
use Carbon\Carbon;
use Jantinnerezo\LivewireAlert\LivewireAlert;
use Livewire\Component;
use Maatwebsite\Excel\Facades\Excel;

class MissingBankReconFilter extends Component
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
        $this->range_start = Carbon::today()->toDateString();
        $this->range_end = Carbon::today()->toDateString();

        $this->parameters = [
            'range_start' =>  Carbon::today()->startOfDay()->toDateTimeString(),
            'range_end' => Carbon::today()->endOfDay()->toDateTimeString(),
        ];

        // Try and fetch data from today
        $this->hasData = MissingBankRecon::whereBetween('created_at', [
                Carbon::today()->startOfDay()->toDateTimeString(),
                Carbon::today()->endOfDay()->toDateTimeString()
            ])->exists();
    }

    protected function rules(){
        return [
            'range_start' => 'required',
            'range_end' =>   'required',
        ];
    }

    public function search(){
        $this->validate();
        $this->parameters['range_start'] =Carbon::parse($this->range_start)->startOfDay()->toDatetimeString();
        $this->parameters['range_end'] = Carbon::parse($this->range_end)->endOfDay()->toDatetimeString();

        $recons = MissingBankRecon::whereBetween('created_at', [Carbon::today()->startOfDay(), Carbon::today()->endOfDay()]);

        if(!$recons){
            $this->hasData = false;
            $this->alert('error','No record found');
        }else{
            $this->hasData = true;
        }

    }

    public function exportExcel(){
        $this->validate();
        $records = MissingBankRecon::whereBetween('created_at', [
                Carbon::today()->startOfDay()->toDateTimeString(),
                Carbon::today()->endOfDay()->toDateTimeString()
            ])->get();

        $fileName = 'bank-recon-report-'.time().'.xlsx';
        $title    = 'For missing bank recons created between '.$this->parameters['range_start'].' and '.$this->parameters['range_end'];

        $this->alert('success', 'Exporting Excel File');
        return Excel::download(new BankReconExport($records, $title), $fileName);
    }

    public function render()
    {
        return view('livewire.payments.missing-bank-recon-filter');
    }
}
