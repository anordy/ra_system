<?php

namespace App\Http\Livewire\Payments;

use App\Exports\BankReconExport;
use App\Models\BankRecon;
use App\Traits\ReconReportTrait;
use Carbon\Carbon;
use Jantinnerezo\LivewireAlert\LivewireAlert;
use Livewire\Component;
use Maatwebsite\Excel\Facades\Excel;

class BankReconFilter extends Component
{
    use ReconReportTrait;
    use LivewireAlert;

    public $range_start;
    public $range_end;
    public $currency = 'all';
    public $today;
    public $hasData=false;
    public $parameters = [];

    public function mount(){
        $this->today = date('Y-m-d');
        $this->range_start = Carbon::today()->toDateString();
        $this->range_end = Carbon::today()->toDateString();

        $this->parameters = [
            'range_start' =>  Carbon::today()->startOfDay()->toDateTimeString(),
            'range_end' => Carbon::today()->endOfDay()->toDateTimeString(),
            'currency' => $this->currency
        ];

        // Try and fetch data from today
        $query = BankRecon::with('bill')
            ->whereBetween('created_at', [
                Carbon::today()->startOfDay()->toDateTimeString(),
                Carbon::today()->endOfDay()->toDateTimeString()
            ]);

        if ($this->currency != 'all'){
            $query->where('currency', $this->currency);
        }

        $this->hasData = $query->exists();
    }

    protected function rules(){
        return [
            'range_start' => 'required',
            'range_end' => 'required',
            'currency' => 'required'
        ];
    }

    public function search(){
        $this->validate();
        $this->parameters['range_start'] =Carbon::parse($this->range_start)->startOfDay()->toDatetimeString();
        $this->parameters['range_end'] = Carbon::parse($this->range_end)->endOfDay()->toDatetimeString();
        $this->parameters['currency'] = $this->currency;

        $recons = BankRecon::query()
            ->with('bill')
            ->whereBetween('created_at', [Carbon::today()->startOfDay(), Carbon::today()->endOfDay()]);

        if ($this->currency != 'all'){
            $recons->where('currency', $this->currency);
        }

        if($recons->count()) {
            $this->hasData = true;
            return;
        }

        $this->hasData = false;
        $this->alert('error','No record found');
    }

    public function exportExcel(){
        $this->validate();
        $query = BankRecon::with('bill')
            ->whereBetween('created_at', [
                Carbon::today()->startOfDay()->toDateTimeString(),
                Carbon::today()->endOfDay()->toDateTimeString()
            ]);

        if ($this->currency != 'all'){
            $query->where('currency', $this->currency);
        }

        $fileName = 'bank-recon-report-'.time().'.xlsx';
        $title    = 'For bank recon created between '.Carbon::parse($this->parameters['range_start'])->toDayDateTimeString().' and '.Carbon::parse($this->parameters['range_end'])->toDayDateTimeString();

        $this->alert('success', 'Exporting Excel File...');
        return Excel::download(new BankReconExport($query->get(), $title), $fileName);
    }

    public function render()
    {
        return view('livewire.payments.bank-recon-filter');
    }
}
