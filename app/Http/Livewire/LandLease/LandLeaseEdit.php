<?php

namespace App\Http\Livewire\LandLease;

use App\Models\BusinessLocation;
use App\Models\District;
use App\Models\LandLease;
use App\Models\LandLeaseFiles;
use App\Models\LandLeaseHistory;
use App\Models\Region;
use App\Models\Role;
use App\Models\TaxPayer;
use App\Models\User;
use App\Models\Ward;
use App\Notifications\DatabaseNotification;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Gate;
use Illuminate\Support\Facades\Log;
use App\Traits\CustomAlert;
use Livewire\Component;
use Livewire\WithFileUploads;

class LandLeaseEdit extends Component
{
    use CustomAlert, WithFileUploads;

    public $name;
    public $email;
    public $phoneNumber;
    public $address;
    public $dpNumber;
    public $region;
    public $district;
    public $ward;
    public $taxpayerName;

    public $landLease;
    public $leaseDocuments;
    public $files = [];

    public function mount($enc_id)
    {
        $this->files[] = ['file' => '', 'name' => ''];
        $this->landLease = LandLease::find(decrypt($enc_id));
        $this->leaseDocuments = $this->leaseDocuments($this->landLease->id);
    }

    public function render()
    {
        return view('livewire.land-lease.land-lease-edit');
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
        $this->customAlert('error', __('You have no Permission to Edit Land Lease'));
        return;

        if (!Gate::allows('lease-edit')) {
            $this->customAlert('error', __('You have no Permission to Edit Land Lease'));
            return;
        }

        // Validate the incoming data
        $this->validate();

        try {
            DB::beginTransaction();

            foreach ($this->files as $fileData) {
                $file = $fileData['file'];
                $filePath = $file->store('/lease_agreement_documents', 'local-admin'); // Store the file and get the file path

                // Update existing lease files if any
                $leaseFiles = LandLeaseFiles::where('land_lease_id', $this->landLease->id)->get();
                if ($leaseFiles->isNotEmpty()) {
                    foreach ($leaseFiles as $leaseFile) {
                        $leaseFile->previous_file_path = $filePath;
                        $leaseFile->approval_status = 'pending';
                        $leaseFile->save();
                    }
                } else {
                    Log::info('No existing lease files found for land lease ID: ' . $this->landLease->id);
                }
            }

            DB::commit();
            $this->createNotification($this->dpNumber);

            // Reset component state after successful upload
            $this->files = [];

            $this->flash('success', 'Files edited successfully, waiting approval');

        } catch (\Exception $e) {
            DB::rollBack();
            Log::error($e->getMessage());
            $this->flash('error', 'Something went wrong');
        }
    }


    public function createNotification($dpNumber)
    {
        $leaseOfficers = User::whereHas('role', function ($query) {
            $query->where('name', 'Land Lease Officer');
        })->get();

        foreach ($leaseOfficers as $leaseOfficer) {
            $leaseOfficer->notify(new DatabaseNotification(
                $subject = 'Land Lease Edit Notification',
                $message = "Lease with DP No $dpNumber been edited by " . auth()->user()->fname . " " . auth()
                        ->user()->lname,
                $href = 'land-lease.list',
            ));
        }
    }

    public function leaseDocuments($id)
    {
        return LandLeaseFiles::select('name', 'file_path')->where('land_lease_id', $id)->get();
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

