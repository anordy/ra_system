<?php

namespace App\Http\Livewire\Approval;

use App\Models\ISIC1;
use App\Models\ISIC2;
use App\Models\ISIC3;
use App\Models\ISIC4;
use App\Traits\WorkflowProcesssingTrait;
use Exception;
use Jantinnerezo\LivewireAlert\LivewireAlert;
use Livewire\Component;

class ApprovalProcessing extends Component
{
    use WorkflowProcesssingTrait, LivewireAlert;
    public $modelId;
    public $modelName;
    public $comment;
    public $isiic_i;
    public $isiic_ii;
    public $isiic_iii;
    public $isiic_iv;


    public $isiiciList = [];
    public $isiiciiList = [];
    public $isiiciiiList = [];
    public $isiicivList = [];


    public function mount($modelName, $modelId)
    {
        $this->modelName = $modelName;
        $this->modelId = $modelId;
        $this->registerWorkflow($modelName, $modelId);
        $this->isiiciList = ISIC1::all();
    }

    public function isiiciChange($value)
    {
        $this->isiiciiList = ISIC2::where('isic1_id', $value)->get();
        $this->isiic_ii = null;
        $this->isiic_iii = null;
        $this->isiic_iv = null;
        $this->isiiciiiList = [];
        $this->isiicivList = [];
    }
    public function isiiciiChange($value)
    {
        $this->isiiciiiList = ISIC3::where('isic2_id', $value)->get();
        $this->isiic_iii = null;
        $this->isiic_iv = null;
        $this->isiicivList = [];
    }
    public function isiiciiiChange($value)
    {
        $this->isiicivList = ISIC4::where('isic3_id', $value)->get();
        $this->isiic_iv = null;
    }


    public function approve($transtion)
    {
        try {
            $this->doTransition($transtion, 'approve');
        } catch (Exception $e) {
            dd($e);
        }
        $this->flash('success', 'Approved successfully',[], redirect()->back()->getTargetUrl());
    }

    public function reject($transtion)
    {
        try {
            $this->doTransition($transtion, 'reject');
        } catch (Exception $e) {
            dd($e);
        }

        $this->flash('success', 'Rejected successfully',[], redirect()->back()->getTargetUrl());
    }


    public function render()
    {
        return view('livewire.approval.processing');
    }
}
