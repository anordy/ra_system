<?php

namespace App\Http\Livewire\Workflow;

use App\Models\Role;
use App\Models\User;
use App\Models\Workflow;
use Exception;
use Illuminate\Support\Facades\Log;
use Jantinnerezo\LivewireAlert\LivewireAlert;
use Livewire\Component;

class WorkflowPlaceUpdateModal extends Component
{

    use LivewireAlert;

    public $name;
    public $workflow;
    public $place;
    public $owner;
    public $operator_type;
    public $operators;
    public $role_id = '';
    public $user_id = '';
    public $users = [];
    public $roles = [];
    public function mount($workflow, $placeName)
    {
        $this->workflow = Workflow::find($workflow);
        $places = json_decode($this->workflow->places, true);
        $this->place = $places[$placeName];
        $this->name = $placeName;
        $this->owner = $this->place['owner'];
        $this->operator_type = $this->place['operator_type'];
        $this->operators = $this->place['operators'];

        if ($this->operator_type == 'user') {
            $this->user_id = User::whereIn('id', $this->operators)->pluck('id');
        } elseif ($this->operator_type == 'role') {
            $this->role_id = Role::whereIn('id', $this->operators)->pluck('id');
        }

        $this->users = User::all();
        $this->roles = Role::all();
    }

    protected function rules()
    {
        return [
            'operator_type' => 'in:user,role',
            'role_id' => 'nullable',
            'user_id' => 'nullable',
        ];
    }


    public function submit()
    {
        $this->validate();

        $place = $this->place;
        $place['operator_type'] = $this->operator_type;
        if ($this->operator_type == 'user') {
            $place['operators'] = collect($this->user_id)->toArray();
        } elseif ($this->operator_type == 'role') {
            $place['operators'] = collect($this->role_id)->toArray();
        }


        $workflowPlaces = json_decode($this->workflow->places, true);

        $workflowPlaces[$this->name] = $place;

        try {
            $this->workflow->places = $workflowPlaces;
            $this->workflow->save();
            $this->flash('success', 'Record added successfully', [], redirect()->back()->getTargetUrl());
        } catch (Exception $e) {
            Log::error($e);
            $this->alert('error', 'Something went wrong, Please contact our support desk for help');
        }
    }

    public function render()
    {
        return view('livewire.workflow.update');
    }
}
