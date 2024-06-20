<?php

namespace App\Http\Livewire\LandLease;

use App\Models\User;
use App\Notifications\DatabaseNotification;
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
    public $files = [];

    public function mount()
    {
        $this->files[] = ['file' => '', 'name' => ''];
    }

    public function render()
    {
        return view('livewire.land-lease.register-land-lease');
    }

    public function rules()
    {
        return [
            'files.*.file' => 'required|mimes:pdf|max:1024|max_file_name_length:100',
            'files.*.name' => 'required|string|max:255',
        ];
    }

    public function submit()
    {
        $this->validate();

        try {
            DB::beginTransaction();
            //$leaseAgreementPath = $this->leaseAgreement->store('/lease_agreement_documents', 'local-admin');

            $landLease = LandLease::create([
                'lease_agreement_path' => 'null',
                'created_by' => Auth::user()->id,
                'lease_status' => 2,
            ]);

            foreach ($this->files as $fileData) {
                $file = $fileData['file'];
                $filePath = $file->store('/lease_agreement_documents', 'local-admin'); // Store the file and get the file path

                // Insert file details into the database
                DB::table('land_lease_files')->insert([
                    'land_lease_id' => $landLease->id,
                    'name' => $fileData['name'],
                    'file_path' => $filePath,
                    'approval_status' => "approved",
                ]);
            }

            // Reset component state after successful upload
            $this->files = [];
            $this->files[] = ['file' => '', 'name' => '']; // Add empty placeholders

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
        $leaseOfficers = User::whereHas('role.permissions', function ($query) {
            $query->where('name', 'land-lease-notification');
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

    public function addFileInput()
    {
        $this->files[] = ['file' => '', 'name' => ''];
    }

    public function removeFileInput($index)
    {
        unset($this->files[$index]);
    }
}
