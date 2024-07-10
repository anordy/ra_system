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
            'files.*.file' => 'required|mimes:pdf|max:3072|max_file_name_length:100',
            'files.*.name' => 'required|string|strip_tag|max:255',
        ];
    }

    public function submit()
    {
        // Validate input
        $this->validate();

        try {
            // Start database transaction
            DB::beginTransaction();

            // Create a new land lease record
            $landLease = LandLease::create([
                'lease_agreement_path' => 'null',
                'created_by' => Auth::user()->id,
                'lease_status' => 2,
            ]);

            // Check if the land lease creation was successful
            if (!$landLease) {
                $this->customAlert('error', __('Something went wrong, please try again later'));
                return;
            }

            // Process each file in the $this->files array
            foreach ($this->files as $fileData) {
                $file = $fileData['file'];
                // Store the file and get the file path
                $filePath = $file->store('/lease_agreement_documents', 'local');

                // Insert file details into the database
                DB::table('land_lease_files')->insert([
                    'land_lease_id' => $landLease->id,
                    'name' => $fileData['name'],
                    'file_path' => $filePath,
                    'approval_status' => 'approved',
                ]);
            }

            // Reset component state after successful upload
            $this->files = [['file' => '', 'name' => '']]; // Add empty placeholders

            // Create notification for lease officers
            $this->createNotification();

            // Commit the transaction
            DB::commit();

            // Display success message and redirect
            $this->customAlert('success', __('Land Lease registered successfully'));
            return redirect()->route('land-lease.approval.list');

        } catch (\Exception $e) {
            // Rollback the transaction in case of an error
            DB::rollBack();
            // Log the error for debugging
            Log::error("REGISTER-LAND-LEASE-EXCEPTION: " . json_encode($e->getMessage()));
            // Display error message
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
                'Land Lease Registration',
                "Land Lease has been registered ",
                'land-lease.list',
                'land-lease',
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
