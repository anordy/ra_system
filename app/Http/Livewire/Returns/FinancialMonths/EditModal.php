<?php

namespace App\Http\Livewire\Returns\FinancialMonths;

use App\Jobs\Bill\UpdateDueDate;
use App\Models\DualControl;
use App\Models\FinancialMonth;
use App\Models\FinancialYear;
use App\Traits\DualControlActivityTrait;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Gate;
use Illuminate\Support\Facades\Log;
use App\Traits\CustomAlert;
use Livewire\Component;

class EditModal extends Component
{
    use CustomAlert, DualControlActivityTrait;

    public $years;
    public $year;
    public $month;
    public $month_number;
    public $day;
    public $edited_month;
    public $old_values;

    public function mount($id)
    {
        if (Gate::allows('setting-user-edit')) {
            abort(403);
        }
        $this->years = FinancialYear::query()->select('id', 'name', 'code', 'active', 'is_approved')->orderByDesc('id')->get();
        $this->edited_month = FinancialMonth::query()->findOrFail(decrypt($id), ['id', 'financial_year_id', 'due_date', 'number']);
        $this->day = (int)date('d', strtotime($this->edited_month->due_date));
        $this->month_number = $this->edited_month->number;
        $this->year = $this->edited_month->financial_year_id;
        $this->old_values = [
            'financial_year_id' => $this->year,
            'number' => $this->month_number,
            'name' => $this->edited_month->name,
            'due_date' =>$this->edited_month->due_date,
            'lumpsum_due_date' => $this->edited_month->lumpsum_due_date,
        ];
    }

    public function rules()
    {
        return [
            'year' => 'required|strip_tag',
            'month_number' => 'required|strip_tag',
            'day' => 'required|strip_tag'
        ];
    }

    public function submit()
    {
        $this->validate();

        $yr = FinancialYear::query()->findOrFail($this->year, ['code']);

        if (Carbon::create($yr['code'], $this->month_number, $this->day)->isWeekend()) {
            $this->customAlert('error', 'The selected day is weekend. Please choose another day');
            return;
        }

        if ($this->edited_month->is_approved == DualControl::NOT_APPROVED) {
            $this->customAlert('error', 'The updated module has not been approved already');
            return;
        }

        $this->month = date('F', mktime(0, 0, 0, $this->month_number));
        $payload = [
            'financial_year_id' => (int)$this->year,
            'number' => (int)$this->month_number,
            'name' => $this->month,
            'due_date' => Carbon::create($yr['code'], $this->month_number, $this->day)->endOfDay()->toDateTimeString(),
            'lumpsum_due_date'  => Carbon::create($yr['code'], $this->month_number)->endOfMonth()->endOfDay()->toDateTimeString(),
        ];

        try {
            DB::beginTransaction();
            $this->edited_month->update($payload);
            $this->triggerDualControl(get_class($this->edited_month), $this->edited_month->id, DualControl::EDIT, 'editing financial month '.$this->edited_month->name. ' '.$this->edited_month->year->code, json_encode($this->old_values), json_encode($payload));
            DB::commit();
            UpdateDueDate::dispatch($this->edited_month);
            $this->customAlert('success', DualControl::SUCCESS_MESSAGE, ['timer'=>8000]);
            return redirect()->route('settings.financial-months');

        } catch (\Throwable $exception) {
            DB::rollBack();
            Log::error($exception);
            $this->customAlert('error', DualControl::ERROR_MESSAGE, ['timer'=>2000]);
            return redirect()->route('settings.financial-months');
        }
    }

    public function render()
    {
        return view('livewire.returns.financial-months.edit-modal');
    }
}
