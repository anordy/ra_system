<?php

namespace App\Http\Livewire\Approval\Business;

use App\Enum\BusinessCorrectionType;
use App\Traits\CustomAlert;
use App\Traits\DualControlActivityTrait;
use Illuminate\Validation\Rule;
use Livewire\Component;

class BusinessRejectModal extends Component
{

    use CustomAlert, DualControlActivityTrait;

    public $comments;
    public $correctionType;


    protected function rules()
    {
        return [
            'comments' => ['required', 'string', 'strip_tag'],
            'correctionType' => ['required', Rule::in(BusinessCorrectionType::getConstants())],
        ];
    }

    public function mount($comments)
    {
        $this->comments = $comments;
    }


    public function submit()
    {
        $this->validate();
        return $this->emit('rejectToCorrection', $this->comments, $this->correctionType);
    }

    public function render()
    {
        return view('livewire.approval.business.business-reject-modal');
    }
}
