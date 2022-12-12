<?php

namespace App\Http\Livewire\LandLease;

use App\Models\LandLease;
use App\Models\LeasePayment;
use App\Models\TaxType;
use App\Services\ZanMalipo\ZmCore;
use App\Services\ZanMalipo\ZmResponse;
use Carbon\Carbon;
use Exception;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Jantinnerezo\LivewireAlert\LivewireAlert;
use Livewire\Component;
use App\Traits\PaymentsTrait;
use Illuminate\Support\Facades\Gate;

class ViewLeasePayment extends Component
{
    use LivewireAlert, PaymentsTrait;
    public $landLease;
    public $taxType;
    public $leasePayment;

    public function mount($enc_id)
    {
        $this->leasePayment = LeasePayment::find(decrypt($enc_id));
        $this->taxType = TaxType::where('code', TaxType::LAND_LEASE)->first();
    }

    public function render()
    {
        return view('livewire.land-lease.view-lease-payment');
    }

}
