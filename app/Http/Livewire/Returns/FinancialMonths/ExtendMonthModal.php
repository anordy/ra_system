<?php

namespace App\Http\Livewire\Returns\FinancialMonths;

use App\Models\FinancialMonth;
use App\Models\FinancialYear;
use App\Models\SevenDaysFinancialMonth;
use App\Models\TaPaymentConfiguration;
use App\Models\TaPaymentConfigurationHistory;
use Carbon\Carbon;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Gate;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Session;
use Jantinnerezo\LivewireAlert\LivewireAlert;
use Livewire\Component;
use App\TaxAgentFee;

class ExtendMonthModal extends Component
{
    use LivewireAlert;

    public $years, $year, $month, $number, $value, $due_date, $min;

    public function mount($value)
    {
        $this->years = FinancialYear::query()->where('active', 0)->orderByDesc('code')->get();
        $this->value = $value;
        $this->month = FinancialMonth::query()->select('id', 'financial_year_id', 'due_date', 'number')->where('id', $this->value)->first();
        $this->number = $this->month->number;
        $this->year = $this->month->financial_year_id;
        $this->min = Carbon::create($this->month->due_date)->addDay(1);
        $this->min = date('Y-m-d', strtotime($this->min));
        $this->due_date = date('Y-m-d', strtotime($this->month->due_date));
    }

    public function submit()
    {
        if (!Gate::allows('setting-financial-month-extend')) {
            abort(403);
        }
        $validate = $this->validate([
            'year' => 'required',
            'number' => 'required',
            'due_date' => 'required|date'
        ],
            [
                'number.required' => 'This field is required',
            ]
        );

        DB::beginTransaction();
        try {
            $payload =
                [
                    'financial_year_id' => $this->year,
                    'number' => $this->number,
                    'due_date' => $this->due_date,
                    'updated_at' => now(),
                ];

            $this->month->update($payload);
            DB::commit();
            $this->flash('success', 'Record updated successfully', [], redirect()->back()->getTargetUrl());

        } catch (\Throwable $exception) {
            DB::rollBack();
            Log::error($exception);

            $this->flash('warning', 'Something went wrong, please contact our support desk for help', [], redirect()->back()->getTargetUrl());

        }
    }

    public function render()
    {
        return view('livewire.returns.financial-months.extend-month-modal');
    }
}
