<?php

namespace App\Http\Livewire\Business\Updates;

use App\Models\AccountType;
use App\Models\Bank;
use Exception;
use Livewire\Component;
use App\Models\Business;
use App\Models\BusinessActivity;
use App\Models\BusinessBank;
use App\Models\BusinessStatus;
use App\Models\BusinessUpdate;
use App\Models\BusinessLocation;
use App\Models\Currency;
use App\Models\District;
use App\Models\Region;
use App\Models\Ward;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Jantinnerezo\LivewireAlert\LivewireAlert;

class ShowChanges extends Component
{

    use LivewireAlert;

    public $business;
    public $business_update;
    private $old_values;
    private $new_values;
    public $business_id;

    public function mount($updateId)
    {
        $this->business_update = BusinessUpdate::find(decrypt($updateId));
        $this->old_values = json_decode($this->business_update->old_values);
        $this->business_id = $this->business_update->business_id;
        $this->new_values = json_decode($this->business_update->new_values);
    }

    public function getNameById($type, $id)
    {
        if ($type == 'business_activities_type_id') {
            return BusinessActivity::find($id)->name;
        } else if ($type == 'currency_id') {
            return Currency::find($id)->name;
        } else if ($type == 'region_id') {
            return Region::find($id)->name;
        } else if ($type == 'district_id') {
            return District::find($id)->name;
        } else if ($type == 'ward_id') {
            return Ward::find($id)->name;
        } else if ($type == 'bank_id') {
            return Bank::find($id)->name;
        } else if ($type == 'account_type_id') {
            return AccountType::find($id)->name;
        }
    }

    public function approve()
    {

        DB::beginTransaction();
        try {
            $new_values = json_decode($this->business_update->new_values, true);

            $business_information_data = $new_values['business_information'];
            $business_location_data = $new_values['business_location'];
            $business_bank_data = $new_values['business_bank'];

            /** Update business information */
            $business = Business::findOrFail($this->business_id);
            $business->update($business_information_data);

            /** Update business location */
            $business_location = BusinessLocation::where('business_id', $this->business_id)->where('is_headquarter', true)->firstOrFail();
            $business_location->update($business_location_data);

            /** Update business bank information */
            $business_bank = BusinessBank::where('business_id', $this->business_id)->firstOrFail();
            $business_bank->update($business_bank_data);

            // On workflow transition change update BusinessUpdates status

            DB::commit();
            $this->redirect(route('business.updatesRequests'));
        } catch (Exception $e) {
            DB::rollBack();
            Log::error($e);
            $this->alert('error', 'Something went wrong');
        }
    }



    public function render()
    {
        return view('livewire.business.updates.show', ['new_values' => $this->new_values, 'old_values' => $this->old_values]);
    }
}
