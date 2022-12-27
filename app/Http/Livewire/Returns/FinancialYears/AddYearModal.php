<?php

namespace App\Http\Livewire\Returns\FinancialYears;

use App\Models\DualControl;
use App\Models\FinancialYear;
use App\Models\TaPaymentConfiguration;
use App\Models\TaPaymentConfigurationHistory;
use App\Traits\DualControlActivityTrait;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Gate;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Session;
use Jantinnerezo\LivewireAlert\LivewireAlert;
use Livewire\Component;
use App\TaxAgentFee;

class AddYearModal extends Component
{
    use LivewireAlert, DualControlActivityTrait;

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
        $validate = $this->validate([
                'name' => 'required',
                'year' => 'required',
            ]
        );

        DB::beginTransaction();
        try {
            $payload = [
                'name' => $this->name,
                'code' => $this->year,
                'active' => 1,
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
