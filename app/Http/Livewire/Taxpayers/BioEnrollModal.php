<?php

namespace App\Http\Livewire\Taxpayers;

use App\Models\Biometric;
use App\Models\KYC;
use Jantinnerezo\LivewireAlert\LivewireAlert;
use Livewire\Component;

class BioEnrollModal extends Component
{
    use LivewireAlert;

    public $finger;
    public $hand;
    public $template;
    public $template1;
    public $image;
    public $kyc;


    public function rules()
    {
        return [
            'image' => 'required',
            'template' => 'required'
        ];
    }

    protected $messages = [
        'template.required' => 'Bio template not fully captured.',
        'image.required' => 'Bio Image not fully captured.',
    ];

    public function mount($kyc, $hand, $finger)
    {
        $this->kyc = KYC::find($kyc);
        $this->finger = $finger;
        $this->hand = $hand;

    }

    public function submit(){
        $this->validate();
        $check = Biometric::where('hand', $this->hand)
            ->where('finger', $this->finger)
            ->where('reference_no', $this->kyc->reference_no)->first();

        if($check){
            $this->alert('error', 'Bio already enrolled');
        }else{
            Biometric::create([
                'reference_no' => $this->kyc->reference_no,
                'hand' => $this->hand,
                'finger' => $this->finger,
                'image' => $this->image,
                'template' => $this->template
            ]);

            $this->flash('success', 'Bio enrolled successfully', [], redirect()->back()->getTargetUrl());
        }
      
    }

    public function render()
    {
        return view('livewire.taxpayers.enroll-modal');
    }
}
