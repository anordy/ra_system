<?php

namespace App\Http\Livewire\Returns\FinancialMonths;

use App\Enum\CustomMessage;
use App\Enum\GeneralConstant;
use App\Enum\ReportStatus;
use App\Models\DualControl;
use App\Models\FinancialMonth;
use App\Models\FinancialYear;
use App\Traits\CustomAlert;
use App\Traits\DualControlActivityTrait;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Gate;
use Illuminate\Support\Facades\Log;
use Livewire\Component;

class AddMonthModal extends Component
{
    use CustomAlert, DualControlActivityTrait;

    public $years;
    public $year;
    public $month;
    public $month_number;
    public $day;
    public $is_leap = false;
    public $is_non_leap = false;

    public function mount()
    {
        if (Gate::allows('setting-user-add')) {
            abort(403);
        }
        $this->years = FinancialYear::query()->select(['id', 'name', 'code', 'active', 'is_approved'])
            ->where('active', 0)
            ->orderByDesc('code')
            ->get();
    }

    public function updated($property)
    {
        try {
            if ($property == ReportStatus::Year) {
                $this->month_number = '';
                $this->day = '';
            }
            if ($property == GeneralConstant::MONTH_NUMBER && $this->month_number == GeneralConstant::TWO_INT) {
                $yr = FinancialYear::query()->findOrFail($this->year, ['id', 'name', 'code', 'active', 'is_approved']);
                if (Carbon::create($yr['code'], $this->month_number)->isLeapYear()) {
                    $this->is_leap = true;
                    $this->is_non_leap = false;
                } else {
                    $this->is_non_leap = true;
                    $this->is_leap = false;
                }
            } else {
                $this->is_non_leap = false;
                $this->is_leap = false;
            }
        } catch (\Exception $exception) {
            Log::error('RETURNS-FINANCIAL-MONTHS-ADD-MONTH-MODAL-UPDATED', [$exception]);
            $this->customAlert('error', CustomMessage::ERROR);
        }
    }

    public function submit()
    {
        if (!Gate::allows('setting-financial-month-add')) {
            abort(403);
        }

        $this->validate([
            'year' => 'required|integer',
            'month_number' => 'required|integer',
        ],
            [
                'month_number.required' => 'This field is required',
            ]
        );
        $yr = FinancialYear::query()->findOrFail($this->year, ['id', 'name', 'code', 'active', 'is_approved']);

        if (Carbon::create($yr['code'], $this->month_number, $this->day)->isWeekend()) {
            $this->customAlert('error', 'The selected day is weekend. Please choose another day');
            return;
        }

        $this->month = date('F', mktime(0, 0, 0, $this->month_number));
        try {
            DB::beginTransaction();
            $financial_month = FinancialMonth::query()->create([
                'financial_year_id' => $this->year,
                'number' => $this->month_number,
                'name' => $this->month,
                'due_date' => Carbon::create($yr['code'], $this->month_number, $this->day)->endOfDay()->toDateTimeString(),
                'lumpsum_due_date' => Carbon::create($yr['code'], $this->month_number)->endOfMonth()->endOfDay()->toDateTimeString(),
            ]);
            if (!$financial_month) throw new \Exception('Failed to save financial month');

            $this->triggerDualControl(get_class($financial_month), $financial_month->id, DualControl::ADD, 'adding financial month ' . $this->month . ' ' . $yr['code']);
            DB::commit();
            $this->customAlert('success', DualControl::SUCCESS_MESSAGE);
            return redirect()->route('settings.financial-months');
        } catch (\Throwable $exception) {
            DB::rollBack();
            Log::error('RETURNS-FINANCIAL-MONTHS-ADD-MONTH-MODAL-UPDATED', [$exception]);
            $this->customAlert('error', DualControl::ERROR_MESSAGE);
            return redirect()->route('settings.financial-months');
        }
    }

    public function render()
    {
        return view('livewire.returns.financial-months.add-month-modal');
    }
}
