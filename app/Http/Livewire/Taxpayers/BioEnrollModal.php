<?php

namespace App\Http\Livewire\Taxpayers;

use App\Models\Biometric;
use App\Models\KYC;
use App\Traits\CustomAlert;
use Illuminate\Support\Facades\Log;
use Livewire\Component;

class BioEnrollModal extends Component
{
    use CustomAlert;

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
        try {
            $this->kyc = KYC::find(decrypt($kyc));

            if(is_null($this->kyc)){
                abort(404);
            }
            $this->finger = $finger;
            $this->hand = $hand;
        } catch (\Exception $exception) {
            Log::error($exception);
            abort(500, 'Something went wrong, please contact your system administrator.');
        }
    }

    public function submit(){
        $this->validate();
        $check = Biometric::where('hand', $this->hand)
            ->where('finger', $this->finger)
            ->where('reference_no', $this->kyc->reference_no)->first();

        if($check){
            $this->customAlert('error', 'Bio already enrolled');
        }else{
            try {
                Biometric::create([
                    'reference_no' => $this->kyc->reference_no,
                    'hand' => $this->hand,
                    'finger' => $this->finger,
                    'image' => $this->image,
                    'template' => $this->template
                ]);

                $this->flash('success', 'Bio enrolled successfully', [], redirect()->back()->getTargetUrl());
            } catch (\Exception $exception) {
                Log::error($exception);
                $this->customAlert('error', 'Something went wrong, please contact the administrator for help');
            }

        }
      
    }

    public function render()
    {
        return view('livewire.taxpayers.enroll-modal');
    }
}
