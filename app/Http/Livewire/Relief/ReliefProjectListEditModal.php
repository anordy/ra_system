<?php

namespace App\Http\Livewire\Relief;

use App\Models\Relief\ReliefMinistry;
use App\Models\Relief\ReliefProjectList;
use App\Models\Relief\ReliefSponsor;
use Exception;
use Illuminate\Support\Facades\Gate;
use Illuminate\Support\Facades\Log;
use App\Traits\CustomAlert;
use Livewire\Component;

class ReliefProjectListEditModal extends Component
{

    use CustomAlert;

    public $name;
    public $description;
    public $rate;
    public $project;
    public $ministry_id;
    public $relief_sponsor_id;
    public $ministries = [];
    public $sponsors = [];

    protected function rules()
    {
        return [
            'name' => 'required|unique:relief_project_lists,name,'.$this->project->id.',id|strip_tag',
            'description' => 'required|strip_tag',
            'rate' => 'required|numeric|min:0|max:100|strip_tag',
        ];
    }

    public function mount($id)
    {
        $this->ministries = ReliefMinistry::all();
        $this->sponsors = ReliefSponsor::all();
        $data = ReliefProjectList::find(decrypt($id));
        if (is_null($data)){
            abort(404, 'Relief Project List not found');
        }
        $this->project = $data;
        $this->name = $data->name;
        $this->description = $data->description;
        $this->rate = $data->rate;
        $this->ministry_id = $data->ministry_id;
        $this->relief_sponsor_id = $data->relief_sponsor_id;
    }

    public function submit()
    {
        if(!Gate::allows('relief-projects-list-edit')){
            abort(403);
        }
        $this->validate();
        try {
            $this->project->update([
                'name' => $this->name,
                'description' => $this->description,
                'rate' => $this->rate,
                'ministry_id' => $this->ministry_id ?? null,
                'relief_sponsor_id' => $this->relief_sponsor_id ?? null,
            ]);
            $this->flash('success', 'Record updated successfully', [], redirect()->back()->getTargetUrl());
        } catch (Exception $e) {
            Log::error($e);
            $this->customAlert('error', 'Something went wrong, please contact the administrator for help');
        }
    }

    public function render()
    {
        return view('livewire.relief.project_list.edit-modal');
    }
}
