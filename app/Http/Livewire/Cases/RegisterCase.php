<?php

namespace App\Http\Livewire\Cases;

use App\Models\CaseAppeal;
use App\Models\CaseProceeding;
use App\Models\CaseStage;
use App\Models\LegalCase;
use Exception;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Jantinnerezo\LivewireAlert\LivewireAlert;
use Livewire\Component;

class RegisterCase extends Component
{

    use LivewireAlert;

    public $tax_inv_id;
    public $date;
    public $case_number;
    public $comment;
    public $court;


    protected function rules()
    {
        return [
            'date' => 'required|date',
            'comment' => 'required',
            'case_number' => 'required',
            'court' => 'required',
        ];
    }


    public function submit()
    {
        $this->validate();
        try {
            DB::beginTransaction();
            $case = LegalCase::query()->create(
                [
                    'tax_investigation_id'=>$this->tax_inv_id,
                    'date_opened'=>$this->date,
                    'case_number'=>$this->case_number,
                    'case_details'=>$this->comment,
                    'court'=>$this->court,
                    'case_stage_id'=>CaseStage::query()->firstOrCreate(['name'=>'Case Opening'])->id,
                ]
            );
            DB::commit();
            redirect()->to(route('cases.show', encrypt($case->id)));
        } catch (Exception $e) {
            DB::rollBack();
            Log::error($e);
            $this->alert('error', 'Something went wrong, please contact our support desk for help');
        }
    }

    public function render()
    {
        return view('livewire.cases.add-case-modal');
    }
}
