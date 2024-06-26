<?php

namespace App\Http\Livewire\Returns\FinancialYears;

use App\Enum\GeneralConstant;
use App\Models\DualControl;
use App\Models\FinancialYear;
use App\Traits\DualControlActivityTrait;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Gate;
use Illuminate\Support\Facades\Log;
use App\Traits\CustomAlert;
use Livewire\Component;

class AddYearModal extends Component
{
    use CustomAlert, DualControlActivityTrait;

    public $name;
    public $year;

    public function updated($property)
    {
        if (is_numeric($this->year)) {
            $down = $this->year + 1;
            $this->name = $this->year . "/" . $down;
        } else {
            $this->name = '';
        }
    }

    public function submit()
    {
        if (!Gate::allows('setting-financial-year-add')) {
            abort(403);
        }

        $this->validate([
                'name' => 'required|alpha',
                'year' => 'required|numeric',
            ]
        );

        DB::beginTransaction();
        try {
            $payload = [
                'name' => $this->name,
                'code' => $this->year,
                'active' => GeneralConstant::ONE_INT,
            ];
            $year = FinancialYear::query()->updateOrCreate($payload);
            $this->triggerDualControl(get_class($year), $year->id, DualControl::ADD, 'adding financial year');
            DB::commit();
            $this->flash('success', DualControl::SUCCESS_MESSAGE, [], redirect()->back()->getTargetUrl());

        } catch (\Throwable $exception) {
            DB::rollBack();
            Log::error($exception);
            $this->flash('warning', DualControl::ERROR_MESSAGE, [], redirect()->back()->getTargetUrl());

        }
    }

    public function render()
    {
        return view('livewire.returns.financial-years.add-year-modal');
    }
}
