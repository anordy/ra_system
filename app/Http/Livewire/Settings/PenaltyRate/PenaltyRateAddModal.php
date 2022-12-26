<?php

namespace App\Http\Livewire\Settings\PenaltyRate;

use Exception;
use Livewire\Component;
use App\Models\PenaltyRate;
use App\Models\FinancialYear;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Gate;
use Jantinnerezo\LivewireAlert\LivewireAlert;

class PenaltyRateAddModal extends Component
{
    use LivewireAlert;

    public $financial_year_id;
    public $rate;
    public $financialYears;
    public $configs = [];

    public function mount() {
        $existingPenaltyRateFinancialYears = PenaltyRate::distinct()->pluck('financial_year_id')->toArray();
        $this->financialYears = FinancialYear::whereNotIn('id', $existingPenaltyRateFinancialYears)->get();
        $this->configs = PenaltyRate::CONFIGURATIONS;
    }

    protected $rules = [
        'configs.*.rate' => 'required|numeric',
        'financial_year_id' => 'required'
    ];

    protected $messages = [
        'configs.*.rate.required' => 'Rate is required.',
    ];

    public function submit()
    {
        if (!Gate::allows('setting-penalty-rate-add')) {
            abort(403);
        }
        $this->validate();
        DB::beginTransaction();
        try {
            foreach ($this->configs as $config) {
                PenaltyRate::create([
                    'financial_year_id' => $this->financial_year_id,
                    'code' => $config['code'],
                    'name' => $config['name'],
                    'rate' => $config['rate'],
                ]);
            }
            
            DB::commit();
            $this->flash('success', 'Record added successfully', [], redirect()->back()->getTargetUrl());
        } catch (Exception $e) {
            DB::rollBack();
            Log::error($e);
            $this->alert('error', 'Something went wrong, Please contact our support desk for help');
        }
    }

    public function render()
    {
        return view('livewire.settings.penalty-rate.add-modal');
    }
}
