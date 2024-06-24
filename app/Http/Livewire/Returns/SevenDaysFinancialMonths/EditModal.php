<?php

namespace App\Http\Livewire\Returns\SevenDaysFinancialMonths;

use App\Models\DualControl;
use App\Models\FinancialYear;
use App\Models\SevenDaysFinancialMonth;
use App\Traits\DualControlActivityTrait;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;
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
        $this->years = FinancialYear::query()->select('id', 'name', 'code', 'is_approved')->orderByDesc('id')->get();
        $this->edited_month = SevenDaysFinancialMonth::findOrFail(decrypt($id), ['id', 'name', 'due_date', 'number', 'financial_year_id', 'is_approved']);
        $this->day = (int)date('m', strtotime($this->edited_month->due_date));
        $this->month_number = $this->edited_month->number;
        $this->year = $this->edited_month->financial_year_id;
        $this->old_values = [
            'financial_year_id' => $this->year,
            'number' => $this->month_number,
            'name' => $this->edited_month->name,
            'due_date' =>$this->edited_month->due_date,
        ];
    }

    public function rules()
    {
        return [
            'year' => 'required|int',
            'month_number' => 'required|int',
            'day' => 'required|int'
        ];
    }

    public function submit()
    {
        $this->validate();

        try {
            $year = FinancialYear::query()->findOrFail($this->year, ['id', 'code']);

            if (Carbon::create($year['code'], $this->month_number, $this->day)->isWeekend()) {
                $this->customAlert('error', 'The selected day is weekend. Please choose another day');
                return;
            }

            if ($this->edited_month->is_approved == DualControl::NOT_APPROVED) {
                $this->customAlert('error', 'The updated module has not been approved already');
                return;
            }

            $this->month = date('F', mktime(0, 0, 0, $this->month_number));
            $payload = [
                'financial_year_id' => $this->year,
                'number' => $this->month_number,
                'name' => $this->month,
                'due_date' => Carbon::create($year['code'], $this->month_number, $this->day)->endOfDay()->toDateTimeString(),
            ];


            DB::beginTransaction();
            $this->edited_month->update($payload);
            $this->triggerDualControl(get_class($this->edited_month), $this->edited_month->id, DualControl::EDIT, 'editing seven days financial month '.$this->edited_month->name. ' '.$this->edited_month->year->code,json_encode($this->old_values), json_encode($payload));
            DB::commit();
            $this->customAlert('success', DualControl::SUCCESS_MESSAGE);
            return redirect()->route('settings.financial-months');

        } catch (\Throwable $exception) {
            DB::rollBack();
            Log::error($exception);
            $this->customAlert('error', DualControl::ERROR_MESSAGE);
            return redirect()->route('settings.financial-months');
        }
    }
    public function render()
    {
        return view('livewire.returns.seven-days-financial-months.edit-modal');
    }
}
