<?php

namespace App\Http\Livewire\LandLease;

use App\Models\LandLease;
use App\Models\LandLeaseFiles;
use App\Models\User;
use App\Notifications\DatabaseNotification;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Gate;
use Illuminate\Support\Facades\Log;
use App\Traits\CustomAlert;
use Livewire\Component;
use Livewire\WithFileUploads;

class LandLeaseRegisterView extends Component
{
    use CustomAlert, WithFileUploads;

    public $name;
    public $email;
    public $phoneNumber;
    public $address;
    public $region;
    public $district;
    public $ward;
    public $taxpayerName;
    public $previousLeaseAgreementPath;

    public $landLease;
    public $comments;
    public $confirmed = false;

    public function mount($enc_id)
    {
        //get land lease
        $this->landLease = LandLease::find(decrypt($enc_id));
        $this->previousLeaseAgreementPath = LandLeaseFiles::select('file_path','name')
            ->where('land_lease_id',
            $this->landLease->id)->get();
    }


    public function render()
    {
        return view('livewire.land-lease.land-lease-register-view');
    }


    public function submit($action)
    {
        if (!Gate::allows('land-lease-approve-registration')) {
            $this->customAlert('error', 'You are not allowed to perform this action, Contact administrator.');
            return;
        }
        try {
            $landLease = $this->landLease;
            //check if it was approved earlier
            if ($landLease->approval_status !== 'pending') {
                $this->customAlert('error', 'This Lease is already actioned, Contact administrator.');
                return;
            }
            switch ($action) {
                case 'approved':
                    $landLease->update(['approval_status' => 'approved', 'approved_by' => Auth::user()->id, 'approved_at' => now
                    (), 'comments' => $this->comments]);
                    break;
                case 'rejected':
                    $landLease->update(['approval_status' => 'rejected', 'is_registered' => false, 'lease_status' => 2,
                        'approved_by' => Auth::user()->id,
                        'approved_at' => now(), 'comments' => $this->comments]);
                    break;
            }

            //$this->createNotification();

            //redirect to route "land-lease.index"
            $this->customAlert('success', "Lease is $action successfully.");
            return redirect()->route('land-lease.approval.list');
        } catch (\Exception $e) {
            DB::rollBack();
            Log::error($e->getMessage());
            $this->customAlert('error', 'Something went wrong, Try again later.');
            return;
        }
    }

    public function createNotification()
    {
        $leaseOfficers = User::whereHas('role', function ($query) {
            $query->where('name', 'Land Lease Officer');
        })->get();

        foreach ($leaseOfficers as $leaseOfficer) {
            $leaseOfficer->notify(new DatabaseNotification(
                $subject = 'Land Lease Approve Notification',
                $message = "Land Lease has been approved by " . auth()->user()->fname . " " . auth()
                        ->user()->lname,
                $href = 'land-lease.list',
            ));
        }
    }


}

