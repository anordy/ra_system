<?php

namespace App\Http\Livewire\Approval;

use App\Events\SendMail;
use App\Events\SendSms;
use App\Models\Business;
use App\Models\BusinessStatus;
use App\Models\ISIC1;
use App\Models\ISIC2;
use App\Models\ISIC3;
use App\Models\ISIC4;
use App\Models\TaxType;
use App\Traits\WorkflowProcesssingTrait;
use Carbon\Carbon;
use Exception;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Jantinnerezo\LivewireAlert\LivewireAlert;
use Livewire\Component;

class ApprovalWaiverProcessing extends Component
{
     use WorkflowProcesssingTrait, LivewireAlert;
    public $modelId;
   


    public function mount($modelName, $modelId)
    {
        $this->modelName = $modelName;
        $this->modelId = $modelId;
        $this->registerWorkflow($modelName, $modelId);
     
    }




    public function approve($transtion)
    {

        if ($this->checkTransition('registration_officer_review')) {


            $business = Business::find($this->subject->id);

        }

        $this->validate([
            'comments' => 'required',
        ]);

        if ($this->checkTransition('director_of_trai_review')) {
        
        }

      
    }

    public function reject($transtion)
    {
        $this->validate([
            'comments' => 'required|string',
        ]);
     
        $this->flash('success', 'Rejected successfully', [], redirect()->back()->getTargetUrl());
    }

    
    public function render()
    {
        return view('livewire.approval.approval-waiver-processing');
    }
}
