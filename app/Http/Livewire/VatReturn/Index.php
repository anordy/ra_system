<?php

namespace App\Http\Livewire\VatReturn;

use App\Models\FinancialMonth;
use App\Models\FinancialYear;
use Illuminate\Support\Facades\DB;
use Livewire\Component;

class Index extends Component
{
    public $years, $months, $year, $month;

    public function mount()
    {
        $this->years = FinancialYear::all();
    }

    public function updated($property)
    {
        if ($property === 'year')
        {
            $this->months = FinancialMonth::where('financial_year_id', $this->year)->get();
//            dd($this->months);
        }
    }

    public function continue()
    {
        $validated = $this->validate([
           'year'=>'required',
           'month'=>'required'
        ]);

        return redirect()->route('vat-return.requests', ['year'=>$this->year, 'month'=>$this->month]);
    }

    public function render()
    {
        return view('livewire.vat-return.index');
    }
}
