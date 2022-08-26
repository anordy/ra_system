<?php

namespace App\Http\Livewire\Cases;

use App\Models\CaseAppeal;
use App\Models\CaseProceeding;
use App\Models\LegalCase;
use Exception;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Jantinnerezo\LivewireAlert\LivewireAlert;
use Livewire\Component;

class AddAppealModel extends Component
{

    use LivewireAlert;

    public $case_id;
    public $date;
    public $appeal_number;
    public $comment;
    public $court_level_id;
    public $decision_id;


    protected function rules()
    {
        return [
            'date' => 'required|date',
            'comment' => 'required',
            'appeal_number' => 'required',
            'court_level_id' => 'required',
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
            CaseAppeal::query()->create(
                [
                    'case_id'=>$this->case_id,
                    'date_opened'=>$this->date,
                    'appeal_number'=>$this->appeal_number,
                    'appeal_details'=>$this->comment,
                    'court_level_id'=>$this->court_level_id,
                ]
            );
            DB::commit();
            redirect()->to(route('cases.show', encrypt($this->case_id)));
        } catch (Exception $e) {
            DB::rollBack();
            Log::error($e);
            $this->alert('error', 'Something went wrong');
        }
    }

    public function render()
    {
        return view('livewire.cases.add-appeal-modal');
    }
}
