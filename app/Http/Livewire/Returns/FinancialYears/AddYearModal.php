<?php

namespace App\Http\Livewire\Returns\FinancialYears;

use App\Models\FinancialYear;
use App\Models\TaPaymentConfiguration;
use App\Models\TaPaymentConfigurationHistory;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Session;
use Jantinnerezo\LivewireAlert\LivewireAlert;
use Livewire\Component;
use App\TaxAgentFee;

class AddYearModal extends Component
{
    use LivewireAlert;

    public $name;
    public $year;

    public function updated($property)
    {
        if (is_numeric($this->year))
        {
            $down = $this->year + 1;
            $this->name = $this->year."/".$down;
        }
        else{
            $this->name = '';
        }
    }

    public function submit()
    {
        $validate = $this->validate([
            'name' => 'required',
            'year' => 'required',
        ]
        );

        DB::beginTransaction();
        try {
            $year = [
              'name'=>$this->name,
              'code'=>$this->year,
            ];
            $yr = FinancialYear::query()->updateOrCreate($year);

            DB::commit();
            $this->flash('success', 'Saved successfully', [], redirect()->back()->getTargetUrl());

        } catch (\Throwable $exception) {
            Log::error($exception);

            $this->flash('warning', 'Internal server error', [], redirect()->back()->getTargetUrl());

        }
    }

    public function render()
    {
        return view('livewire.returns.financial-years.add-year-modal');
    }
}
