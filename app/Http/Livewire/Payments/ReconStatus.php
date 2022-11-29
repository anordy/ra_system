<?php

namespace App\Http\Livewire\Payments;

use Livewire\Component;
use Jantinnerezo\LivewireAlert\LivewireAlert;
use App\Models\ZmRecon;
use App\Services\ZanMalipo\GepgResponse;

class ReconStatus extends Component
{
    use LivewireAlert, GepgResponse;

    public $recon;
    public function mount($recon)
    {
        $this->recon = $recon;
    }

    public function refresh()
    {
        $this->recon = ZmRecon::findOrFail($this->recon->id);
    }

    public function getGepgStatus($code)
    {
        return $this->getResponseCodeStatus($code)['message'];
    }


    public function render()
    {
        return view('livewire.payments.recon-status');
    }
}
