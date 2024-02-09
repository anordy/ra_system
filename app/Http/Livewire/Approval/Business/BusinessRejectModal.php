<?php

namespace App\Http\Livewire\Approval\Business;

use App\Models\District;
use App\Models\DualControl;
use App\Models\Region;
use App\Traits\DualControlActivityTrait;
use Carbon\Carbon;
use Exception;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Gate;
use Illuminate\Support\Facades\Log;
use App\Traits\CustomAlert;
use Livewire\Component;

class BusinessRejectModal extends Component
{

    use CustomAlert, DualControlActivityTrait;

    public $comments;
    public $correctionType;


    protected function rules()
    {
        return [
            'comments' => 'required',
            'correctionType' => 'required',
        ];
    }

    public function mount($comments)
    {
        $this->comments = $comments;
    }


    public function submit()
    {
        $this->validate();

        return $this->emit('rejectToCorrection', $this->comments, $this->correctionType);

        DB::beginTransaction();
        try {
            $district = District::create([
                'name' => $this->name,
                'region_id' => $this->region_id,
                'created_at' =>Carbon::now()
            ]);
            $this->triggerDualControl(get_class($district), $district->id, DualControl::ADD, 'adding new district '.$this->name.'');
            DB::commit();
            $this->customAlert('success', DualControl::SUCCESS_MESSAGE, ['timer' => 8000]);
            return redirect()->route('settings.district.index');
        } catch (Exception $e) {
            DB::rollBack();
            Log::error($e);
            $this->customAlert('error', DualControl::ERROR_MESSAGE, ['timer' => 2000]);
            return redirect()->route('settings.district.index');
        }
    }

    public function render()
    {
        return view('livewire.approval.business.business-reject-modal');
    }
}
