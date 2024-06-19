<?php

namespace App\Http\Livewire\LandLease;

use App\Models\User;
use App\Notifications\DatabaseNotification;
use Illuminate\Auth\Access\Gate;
use Illuminate\Support\Facades\Auth;
use Livewire\Component;
use Livewire\WithFileUploads;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use App\Traits\CustomAlert;

use App\Models\LandLease;
use App\Traits\CheckReturnConfigurationTrait;

class RegisterLandLease extends Component
{
    use CustomAlert, WithFileUploads, CheckReturnConfigurationTrait;

    public $leaseAgreement;

    public function mount()
    {

    }

    public function render()
    {
        return view('livewire.land-lease.register-land-lease');
    }

    public function rules()
    {
        return [
            'leaseAgreement' => 'required|mimes:pdf|max:1024|max_file_name_length:100',
        ];
    }

    public function submit()
    {
        $this->validate();

        try {
            DB::beginTransaction();
            $leaseAgreementPath = $this->leaseAgreement->store('/lease_agreement_documents', 'local-admin');

            $landLease = LandLease::create([
                'lease_agreement_path' => $leaseAgreementPath,
                'created_by' => Auth::user()->id,
                'lease_status' => 2,
            ]);

            if ($landLease) {

                $this->createNotification();
                DB::commit();
                $this->customAlert('success', __('Land Lease registered successfully'));
                return redirect()->route('land-lease.approval.list');
            }
            $this->customAlert('error', __('Something went wrong, Try again later'));
            return redirect()->back();
        } catch (\Exception $e) {
            DB::rollBack();
            Log::error("REGISTER-LAND-LEASE-EXCEPTION: " . json_encode($e->getMessage()));
            $this->customAlert('error', __('Failed to register lease'));
        }
    }

    public function createNotification()
    {
        $leaseOfficers = User::whereHas('role', function ($query) {
            $query->where('name', 'Land Lease Officer');
        })->get();

        foreach ($leaseOfficers as $leaseOfficer) {
            $leaseOfficer->notify(new DatabaseNotification(
                'Land Lease Approve Notification',
                "Land Lease has been approved by " . auth()->user()->fname . " " . auth()
                    ->user()->lname,
                'land-lease.list',
            ));
        }
    }
}
