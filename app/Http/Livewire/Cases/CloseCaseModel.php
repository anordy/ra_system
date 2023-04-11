<?php

namespace App\Http\Livewire\Cases;

use App\Models\CaseProceeding;
use App\Models\CaseStage;
use App\Models\LegalCase;
use Exception;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use App\Traits\CustomAlert;
use Livewire\Component;

class CloseCaseModel extends Component
{

    use CustomAlert;

    public $case_id;
    public $date;
    public $comment;
    public $outcome_id;


    protected function rules()
    {
        return [
            'date' => 'required|date',
            'comment' => 'required|strip_tag',
            'outcome_id' => 'required|numeric',
        ];
    }

    public function mount($case_id)
    {
        $this->case_id = $case_id;
    }


    public function submit()
    {
        $stage_id = CaseStage::query()->firstOrCreate(['name'=>'Closed'])->id;
        $this->validate();
        try {
            DB::beginTransaction();
            CaseProceeding::query()->create(
                [
                    'case_id'=>$this->case_id,
                    'date'=>$this->date,
                    'comment'=>$this->comment,
                    'case_stage_id'=>$stage_id,
                ]
            );

            LegalCase::query()
                ->where(['id'=>$this->case_id])
                ->update([
                    'case_stage_id'=>$stage_id,
                    'case_outcome_id'=>$this->outcome_id,
                    'date_closed'=>$this->date,
                ]);
            DB::commit();
            redirect()->to(route('cases.show', encrypt($this->case_id)));
        } catch (Exception $e) {
            DB::rollBack();
            Log::error($e);
            $this->customAlert('error', 'Something went wrong, please contact the administrator for help');
        }
    }

    public function render()
    {
        return view('livewire.cases.close-case-modal');
    }
}
