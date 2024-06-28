<?php

namespace App\Http\Livewire\Cases;

use App\Models\CaseProceeding;
use App\Models\LegalCase;
use Exception;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use App\Traits\CustomAlert;
use Livewire\Component;

class AddProceedingModel extends Component
{

    use CustomAlert;

    public $case_id;
    public $date;
    public $comment;
    public $stage_id;
    public $decision_id;


    protected function rules()
    {
        return [
            'date' => 'required|date',
            'comment' => 'required|strip_tag',
            'stage_id' => 'required|numeric',
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
            DB::beginTransaction();
            CaseProceeding::query()->create(
                [
                    'case_id' => $this->case_id,
                    'date' => $this->date,
                    'comment' => $this->comment,
                    'case_stage_id' => $this->stage_id,
                    'case_decision_id' => $this->decision_id,
                ]
            );

            LegalCase::query()
                ->where(['id' => $this->case_id])
                ->update(['case_stage_id' => $this->stage_id]);
            DB::commit();
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
        return view('livewire.cases.add-proceeding-modal');
    }
}
