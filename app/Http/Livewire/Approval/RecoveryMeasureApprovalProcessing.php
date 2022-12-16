<?php

namespace App\Http\Livewire\Approval;

use App\Enum\RecoveryMeasureStatus;
use Exception;
use Livewire\Component;
use App\Models\Debts\DebtRecoveryMeasure;
use App\Models\Debts\RecoveryMeasure;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use App\Models\Debts\RecoveryMeasureCategory;
use App\Traits\WorkflowProcesssingTrait;
use Illuminate\Support\Collection;
use Jantinnerezo\LivewireAlert\LivewireAlert;

class RecoveryMeasureApprovalProcessing extends Component
{

    use LivewireAlert, WorkflowProcesssingTrait;

    public $recovery_measure_categories = [];
    public $debtId;
    public $debt;
    public $modelName;
    public $recovery_measures;
    public $selected_recovery_measures;
    public $comments;
    public $initializedRecMeasure;

    public function mount($debt)
    {
        $this->debtId = $debt->id;
        $this->recovery_measure_categories = RecoveryMeasureCategory::all();
        $this->debt = $debt;
        $this->modelName = get_class($debt);

        $this->initializedRecMeasure = RecoveryMeasure::where('debt_id', $this->debtId)
                ->where('debt_type', $this->modelName)
                ->get()
                ->first();

        if ($this->initializedRecMeasure == null) {
            $this->initializedRecMeasure = RecoveryMeasure::updateOrCreate([
                'debt_id' => $this->debtId,
                'debt_type' => $this->modelName
            ],[
                'status' => 'unassigned',
            ]);
        }
            
        $this->registerWorkflow(get_class($this->initializedRecMeasure), $this->initializedRecMeasure->id);

        if ($this->checkTransition('commissioner_review') || $this->checkTransition('assignment_corrected')) {
            $this->recovery_measures = $this->initializedRecMeasure->measures->pluck('recovery_measure_category_id');
            $this->selected_recovery_measures = $this->recovery_measures;
        }
    }


    public function approve($transition)
    {
        $transition = $transition['data']['transition'];
        $this->validate(['recovery_measures' => 'required']);
        DB::beginTransaction();
        try {

            if ($this->checkTransition('crdm_assign')) {
                if ($this->recovery_measures instanceof Collection) {
                    $measures = $this->recovery_measures->toArray();
                } else {
                    $measures = $this->recovery_measures;
                }

                $payload = array_map(function ($measures) {
                    return [
                        'recovery_measure_category_id'    => $measures,
                        'recovery_measure_id'    => $this->initializedRecMeasure->id,
                    ];
                }, $measures);

                $this->initializedRecMeasure->update(['status' => RecoveryMeasureStatus::PENDING]);
                DebtRecoveryMeasure::insert($payload);
            }

            if ($this->checkTransition('commissioner_review')) {
                if ($this->recovery_measures instanceof Collection) {
                    $measures = $this->recovery_measures->toArray();
                } else {
                    $measures = $this->recovery_measures;
                }

                $payload = array_map(function ($measures) {
                    return [
                        'recovery_measure_category_id'    => $measures,
                        'recovery_measure_id'    => $this->initializedRecMeasure->id,
                    ];
                }, $measures);

                DebtRecoveryMeasure::where('recovery_measure_id', $this->initializedRecMeasure->id)->delete();
                DebtRecoveryMeasure::insert($payload);
                $this->initializedRecMeasure->update(['status' => RecoveryMeasureStatus::APPROVED]);
            }

            if ($this->checkTransition('assignment_corrected')) {

                if ($this->recovery_measures instanceof Collection) {
                    $measures = $this->recovery_measures->toArray();
                } else {
                    $measures = $this->recovery_measures;
                }

                $payload = array_map(function ($measures) {
                    return [
                        'recovery_measure_id'    => $this->initializedRecMeasure->id,
                        'recovery_measure_category_id' => $measures
                    ];
                }, $measures);

                $this->initializedRecMeasure->update(['status' => RecoveryMeasureStatus::PENDING]);
                DebtRecoveryMeasure::where('recovery_measure_id', $this->initializedRecMeasure->id)->delete();
                DebtRecoveryMeasure::insert($payload);
            }

            $this->doTransition($transition, ['status' => 'agree', 'comment' => $this->comments]);

            DB::commit();
            $this->flash('success', 'Approved successfully', [], redirect()->back()->getTargetUrl());
        } catch (Exception $e) {
            Log::error($e);
            DB::rollback();
            $this->alert('error', 'Something went wrong');
        }
    }

    public function reject($transition)
    {
        $transition = $transition['data']['transition'];
        DB::beginTransaction();
        try {

            if ($this->checkTransition('assignment_incorrect')) {
                $this->initializedRecMeasure->update(['status' => RecoveryMeasureStatus::CORRECTION]);
            }

            $this->doTransition($transition, ['status' => 'agree', 'comment' => $this->comments]);

            DB::commit();
            $this->flash('success', 'Approved successfully', [], redirect()->back()->getTargetUrl());
        } catch (Exception $e) {
            Log::error($e);
            DB::rollback();
            $this->alert('error', 'Something went wrong');
        }
    }

    protected $listeners = [
        'approve', 'reject'
    ];

    public function confirmPopUpModal($action, $transition)
    {
        $this->alert('warning', 'Are you sure you want to complete this action?', [
            'position' => 'center',
            'toast' => false,
            'showConfirmButton' => true,
            'confirmButtonText' => 'Confirm',
            'onConfirmed' => $action,
            'showCancelButton' => true,
            'cancelButtonText' => 'Cancel',
            'timer' => null,
            'data' => [
                'transition' => $transition
            ],

        ]);
    }

    public function render()
    {
        return view('livewire.approval.debts.recovery-measure-approval-processing');
    }
}
