<?php

namespace App\Http\Livewire;

use App\Models\DualControl;
use App\Models\TaPaymentConfiguration;
use App\Models\TaPaymentConfigurationHistory;
use App\Traits\DualControlActivityTrait;
use App\Traits\ValidationTrait;
use App\Traits\VerificationTrait;
use Exception;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Session;
use Jantinnerezo\LivewireAlert\LivewireAlert;
use Livewire\Component;
use App\TaxAgentFee;
use Illuminate\Support\Facades\Gate;

class TaxAgentFeeModal extends Component
{
    use LivewireAlert, DualControlActivityTrait, VerificationTrait;

    public $category;
    public $duration;
    public $nationality;
    public $old_values;
    public $new_values;

    public function submit()
    {
        if (!Gate::allows('tax-consultant-fee-configuration-add')) {
            abort(403);
        }

        $validate = $this->validate([
            'category' => 'required',
            'duration' => 'required',
            'nationality' => 'required',
        ],
            [
                'nationality.required' => 'This field is required',
            ]
        );

        DB::beginTransaction();
        try {
            $duration = TaPaymentConfiguration::where('category', '=', $this->category)
                ->where('is_citizen', $this->nationality)
                ->first();

            $this->new_values = [
                'category' => $this->category,
                'duration' => $this->duration,
                'is_citizen' => $this->nationality,
                'created_by' => Auth::id(),
            ];
            if ($duration == null) {
                $agent_duration = TaPaymentConfiguration::create($this->new_values);
                // Get ci_payload
                if (!$this->sign($agent_duration)) {
                    throw new Exception('Failed to verify consultant duration.');
                }
                $this->triggerDualControl(get_class($agent_duration), $agent_duration->id, DualControl::ADD, 'adding tax consultant duration for '.$this->category);

            } else {

                $this->old_values = [
                    'tapc_id' => $duration->id,
                    'category' => $duration->category,
                    'duration' => $duration->duration,
                    'is_citizen' => $duration->is_citizen,
                    'created_by' => $duration->created_by,
                ];

                if (!$this->verify($duration)) {
                    throw new Exception('Failed to verify consultant duration.');
                }
                TaPaymentConfigurationHistory::query()->create($this->old_values);

                $duration->delete();

                $agent_duration = TaPaymentConfiguration::query()->create($this->new_values);
                // Get ci_payload
                if (!$this->sign($agent_duration)) {
                    throw new Exception('Failed to verify consultant duration.');
                }

                $this->triggerDualControl(get_class($agent_duration), $agent_duration->id, DualControl::EDIT, 'editing tax consultant duration', json_encode($this->old_values), json_encode($this->new_values));
            }

            DB::commit();
            $this->alert('success', 'Saved successfully');
            return redirect()->route('settings.tax-consultant-duration');
        } catch (\Throwable $exception) {
            Log::error($exception);
            $this->alert('warning', 'Something went wrong, Please contact an admin');
            return redirect()->route('settings.tax-consultant-duration');

        }
    }

    public function render()
    {
        return view('livewire.tax-agent-fee-modal');
    }
}
