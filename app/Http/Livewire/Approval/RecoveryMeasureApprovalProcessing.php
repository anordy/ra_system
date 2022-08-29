<?php

namespace App\Http\Livewire\Approval;

use App\Enum\RecoveryMeasureStatus;
use Exception;
use Livewire\Component;
use App\Models\Debts\Debt;
use App\Models\Debts\RecoveryMeasure;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use App\Models\Debts\RecoveryMeasureCategory;
use App\Traits\WorkflowProcesssingTrait;
use Jantinnerezo\LivewireAlert\LivewireAlert;

class RecoveryMeasureApprovalProcessing extends Component
{

    use LivewireAlert, WorkflowProcesssingTrait;

    public $recovery_measure_categories = [];
    public $debtId;
    public $debt;
    public $recovery_measures;
    public $selected_recovery_measures;
    public $comments;

    public function mount($debtId)
    {
        $this->debtId = $debtId;
        $this->recovery_measure_categories = RecoveryMeasureCategory::all();
        $this->debt = Debt::findOrFail($debtId);
        $this->registerWorkflow(get_class($this->debt), $this->debt->id);

        if($this->checkTransition('commissioner_review') || $this->checkTransition('assignment_corrected')) {
            $this->recovery_measures = RecoveryMeasure::where('debt_id', $debtId);
            $this->selected_recovery_measures = $this->recovery_measures;
        }

    }


    public function approve($transition)
    {
        $this->validate(['recovery_measures' => 'required']);
        DB::beginTransaction();
        try {
            
            if($this->checkTransition('crdm_assign')) {
                $measures = $this->recovery_measures->toArray();

                $payload = array_map(function ($measures) {
                    return [
                        'recovery_measure_id'    => $measures,
                        'debt_id' => $this->debtId
                    ];
                }, $measures); 
                $this->debt->update(['recovery_measure_status' => RecoveryMeasureStatus::PENDING]);
                RecoveryMeasure::insert($payload);
            }

            if($this->checkTransition('commissioner_review')) {
                $measures = $this->recovery_measures->toArray();

                $payload = array_map(function ($measures) {
                    return [
                        'recovery_measure_id'    => $measures,
                        'debt_id' => $this->debtId
                    ];
                }, $measures); 

                RecoveryMeasure::where('debt_id', $this->debtId)->delete();
                RecoveryMeasure::insert($payload);
                $this->debt->update(['recovery_measure_status' => RecoveryMeasureStatus::APPROVED]);

            }

            if($this->checkTransition('assignment_corrected')) {
                $measures = $this->recovery_measures->toArray();

                $payload = array_map(function ($measures) {
                    return [
                        'recovery_measure_id'    => $measures,
                        'debt_id' => $this->debtId
                    ];
                }, $measures); 
                $this->debt->update(['recovery_measure_status' => RecoveryMeasureStatus::PENDING]);
                RecoveryMeasure::where('debt_id', $this->debtId)->delete();
                RecoveryMeasure::insert($payload);
            }

            $this->doTransition($transition, ['status' => 'agree', 'comment' => $this->comments]);

            DB::commit();
            $this->flash('success', 'Approved successfully', [], redirect()->back()->getTargetUrl());

        } catch (Exception $e) {
            dd($e);
            Log::error($e);
            DB::rollback();
            $this->alert('error', 'Something went wrong');
        }
        
    }

    public function reject($transition)
    {
        DB::beginTransaction();
        try {
            
            if($this->checkTransition('assignment_incorrect')) {
                $this->debt->update(['recovery_measure_status' => RecoveryMeasureStatus::CORRECTION]);
                
            }

            $this->doTransition($transition, ['status' => 'agree', 'comment' => $this->comments]);

            DB::commit();
            $this->flash('success', 'Approved successfully', [], redirect()->back()->getTargetUrl());

        } catch (Exception $e) {
            dd($e);
            Log::error($e);
            DB::rollback();
            $this->alert('error', 'Something went wrong');
        }
        
    }

    public function render()
    {
        return view('livewire.approval.debts.recovery-measure-approval-processing');
    }
}
