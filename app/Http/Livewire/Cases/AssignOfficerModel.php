<?php

namespace App\Http\Livewire\Cases;

use App\Models\CaseProceeding;
use App\Models\LegalCase;
use Exception;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use App\Traits\CustomAlert;
use Livewire\Component;

class AssignOfficerModel extends Component
{

    use CustomAlert;

    public $case_id;
    public $user_id;


    protected function rules()
    {
        return [
            'user_id' => 'required|numeric',
        ];
    }

    public function mount($case_id)
    {
        $this->case_id = $case_id;
    }


    public function submit()
    {
        $this->validate();
        try {
            LegalCase::query()
                ->where(['id' => $this->case_id])
                ->update(['assigned_officer_id' => $this->user_id]);
            session()->flash('success', 'User assigned!');
            redirect()->to(route('cases.show', encrypt($this->case_id)));
        } catch (Exception $e) {
            DB::rollBack();
            Log::error('Error: ' . $e->getMessage(), [
                'file' => $e->getFile(),
                'line' => $e->getLine(),
                'trace' => $e->getTraceAsString(),
            ]);
            $this->customAlert('error', 'Something went wrong, please contact the administrator for help');
        }
    }

    public function render()
    {
        return view('livewire.cases.assign-officer-modal');
    }
}
