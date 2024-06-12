<?php

namespace App\Http\Livewire\Cases;

use App\Models\CaseAppeal;
use App\Models\CaseProceeding;
use App\Models\CaseStage;
use App\Models\LegalCase;
use Exception;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use App\Traits\CustomAlert;
use Livewire\Component;

class CloseAppealModel extends Component
{

    use CustomAlert;

    public $appeal_id;
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

    public function mount($appeal_id)
    {
        $this->appeal_id = $appeal_id;
    }


    public function submit()
    {
        $this->validate();
        try {
            DB::beginTransaction();
            CaseAppeal::query()
                ->where(['id' => $this->appeal_id])
                ->update([
                    'case_outcome_id' => $this->outcome_id,
                    'outcome_details' => $this->comment,
                    'date_closed' => $this->date,
                ]);
            DB::commit();
            redirect()->to(route('cases.show', encrypt(CaseAppeal::query()->find($this->appeal_id)->case_id)));
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
        return view('livewire.cases.close-appeal-modal');
    }
}
