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

class AddMonthModal extends Component
{
    use LivewireAlert;

    public $years, $year, $month, $number ;

    public function mount()
    {
        $this->years = FinancialYear::query()->where('active',0)->orderByDesc('code')->get();
    }

    public function submit()
    {
        if (!Gate::allows('setting-financial-month-add')) {
            abort(403);
        }

        $validate = $this->validate([
            'year' => 'required',
            'number' => 'required',
        ],
            [
                'number.required' => 'This field is required',
            ]
        );

        $this->month = date( 'F', strtotime( "$this->number/12/10" ));

        DB::beginTransaction();
        try {
            $yr = FinancialYear::query()->findOrFail($this->year);

            $financial_month = FinancialMonth::query()->create([
                'financial_year_id' => $this->year,
                'number'            => $this->number,
                'name'              => $this->month,
                'due_date'          => Carbon::create($yr['code'], $this->number, 20)->toDateTimeString(),
            ]);

            $seven_days = SevenDaysFinancialMonth::query()->create([
                'financial_year_id' => $this->year,
                'number'            => $this->number,
                'name'              => $this->month,
                'due_date'          => Carbon::create($yr['code'], $this->number, 7)->toDateTimeString(),
            ]);
            DB::commit();
            $this->flash('success', 'Saved successfully', [], redirect()->back()->getTargetUrl());

        } catch (\Throwable $exception) {
            DB::rollBack();
            Log::error($exception);

            $this->flash('warning', 'Something went wrong', [], redirect()->back()->getTargetUrl());

        }
    }

    public function render()
    {
        return view('livewire.returns.financial-months.add-month-modal');
    }
}
