<?php

namespace App\Http\Livewire\Returns\SevenDaysFinancialMonths;

use App\Models\DualControl;
use App\Models\FinancialMonth;
use App\Models\FinancialYear;
use App\Models\SevenDaysFinancialMonth;
use App\Traits\DualControlActivityTrait;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Gate;
use Illuminate\Support\Facades\Log;
use Jantinnerezo\LivewireAlert\LivewireAlert;
use Livewire\Component;

class AddMonthModal extends Component
{
    use LivewireAlert, DualControlActivityTrait;

    public $years;
    public $year;
    public $month;
    public $month_number;
    public $day;
    public $is_leap = false;
    public $is_non_leap = false;

    public function mount()
    {
        $this->years = FinancialYear::query()->where('active', 0)->orderByDesc('code')->get();
    }

    public function updated($property)
    {
        $yr = FinancialYear::query()->findOrFail($this->year);
        if ($property == 'year')
        {
            $this->month_number = '';
            $this->day  = '';
        }
        if ($property == 'month_number' && $this->month_number == 2)
        {
            if (Carbon::create($yr['code'], $this->month_number)->isLeapYear()) {
                $this->is_leap = true;
                $this->is_non_leap = false;
            }
            else{
                $this->is_non_leap = true;
                $this->is_leap = false;
            }
        }
        else{
            $this->is_non_leap = false;
            $this->is_leap = false;
        }
    }

    public function submit()
    {
        if (!Gate::allows('setting-financial-month-add')) {
            abort(403);
        }

        $this->validate([
            'year' => 'required',
            'month_number' => 'required',
        ],
            [
                'month_number.required' => 'This field is required',
            ]
        );
        if ($this->day > 8) {
            $this->alert('error', 'The day field must be less than or equal to seven days');
            return;
        }
        $yr = FinancialYear::query()->findOrFail($this->year);
        if (Carbon::create($yr['code'], $this->month_number, $this->day)->isWeekend()) {
            $this->alert('error', 'The selected day is weekend. Please choose another day');
            return;
        }
        $this->month = date('F', mktime(0, 0, 0, $this->month_number));
        DB::beginTransaction();
        try {
            $seven_days = SevenDaysFinancialMonth::query()->create([
                'financial_year_id' => $this->year,
                'number' => $this->month_number,
                'name' => $this->month,
                'due_date' => Carbon::create($yr['code'], $this->month_number, $this->day)->endOfDay()->toDateTimeString(),
            ]);
            $this->triggerDualControl(get_class($seven_days), $seven_days->id, DualControl::ADD, 'adding seven days financial month '.$this->month.' '.$yr['code']);
            DB::commit();
            $this->alert('success', DualControl::SUCCESS_MESSAGE, ['timer'=>8000]);
            return redirect()->route('settings.financial-months');

        } catch (\Throwable $exception) {
            DB::rollBack();
            Log::error($exception);
            $this->alert('error', DualControl::ERROR_MESSAGE, ['timer'=>2000]);
            return redirect()->route('settings.financial-months');

        }
    }

    public function render()
    {
        return view('livewire.returns.seven-days-financial-months.add-month-modal');
    }
}
