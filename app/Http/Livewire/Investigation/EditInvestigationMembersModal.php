<?php

namespace App\Http\Livewire\Investigation;

use App\Jobs\Workflow\WorkflowUpdateActorsForTask;
use App\Models\Investigation\TaxInvestigationOfficer;
use App\Models\Role;
use App\Models\User;
use App\Rules\NotInArray;
use App\Traits\CustomAlert;
use App\Traits\WorkflowProcesssingTrait;
use Exception;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\Validation\Rules\NotIn;
use Livewire\Component;

class EditInvestigationMembersModal extends Component
{
    use WorkflowProcesssingTrait, CustomAlert;
    public $modelId;
    public $modelName;
    public $investigation;

    public $staffs = [];
    public $subRoles = [];
    public $teamLeader;
    public $teamMembers = [];
    public $task;

    protected $listeners = [
        'confirmed'
    ];


    public function mount($model){
        $this->investigation = decrypt($model);
        $this->modelName = get_class($this->investigation);
        $this->modelId = $this->investigation->id;

        $this->registerWorkflow($this->modelName, $this->modelId);

        $this->subject = $this->getSubject();
        $this->task = $this->subject->pinstancesActive;
        $this->initializeStaffAndRoles();
        $this->getLeaderId();
    }

    private function initializeStaffAndRoles() {
        if ($this->task != null) {
            $operators = json_decode($this->task->operators, true) ?: [];
            $roles = Role::query()->whereIn('id', $operators)->get()->pluck('id')->toArray();
            $this->subRoles = Role::query()->whereIn('report_to', $roles)->get();
            $this->staffs = User::query()->whereIn('role_id', $this->subRoles->pluck('id')->toArray())->get();
            $this->staffs = User::all();
        }
    }

    private function getLeaderId(){
        foreach ($this->investigation->officers as $officer){
            if ($officer->team_leader == 1){
                $this->teamLeader = $officer->user_id;
            }else{
                $this->teamMembers[] = $officer->user_id;
            }
        }
    }

    private function getSubject() {
        return app($this->modelName)->findOrFail($this->modelId);
    }


    public function confirmPopUpModal() {
        $this->customAlert('warning', __('Are you sure you want to complete this action?'), [
            'position' => 'center',
            'toast' => false,
            'showConfirmButton' => true,
            'confirmButtonText' => 'Confirm',
            'onConfirmed' => 'confirmed',
            'showCancelButton' => true,
            'cancelButtonText' => 'Cancel',
            'timer' => null,

        ]);
    }

    public function confirmed(){
        $this->validateAssignOfficers();

        if ($this->subject->officers()->exists()) {
            $this->subject->officers()->delete();
        }

        DB::beginTransaction();
        try {
            TaxInvestigationOfficer::create([
                'investigation_id' => $this->subject->id,
                'user_id' => $this->teamLeader,
                'team_leader' => true,
            ]);

            foreach ($this->teamMembers as $member){
                TaxInvestigationOfficer::create([
                    'investigation_id' => $this->subject->id,
                    'user_id' => $member,
                ]);
            }
            $this->subject->save();
            DB::commit();
            $operators = [intval($this->teamLeader), intval($this->teamMembers)];
            dispatch(new WorkflowUpdateActorsForTask($this->subject->pinstance, $operators));
            $this->flash('success', 'Team members updated', [], redirect()->back()->getTargetUrl());
        }catch (Exception $ex){
            DB::rollBack();
            Log::error("ASSIGNING OFFICERS: ".$ex->getMessage());
            $this->flash('error', 'Could not update Team members!', [], redirect()->back()->getTargetUrl());
        }
    }

    private function validateAssignOfficers() {
        $this->validate(
            [
                'teamLeader' => ['required', new NotInArray([$this->teamMembers])],
                'teamMembers.*' => ['required', new NotIn([$this->teamLeader])],
            ],
            [
                'teamLeader.not_in_array' => 'Duplicate already exists as team member',
                'teamMembers.*.not_in' => 'Duplicate already exists as team leader'
            ]
        );
    }

    public function render()
    {
        return view('livewire.investigation.edit-investigation-members-modal');
    }
}
