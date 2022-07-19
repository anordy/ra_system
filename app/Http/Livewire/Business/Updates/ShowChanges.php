<?php

namespace App\Http\Livewire\Business\Updates;

use Exception;
use Livewire\Component;
use App\Models\Business;
use App\Models\BusinessStatus;
use App\Models\BusinessUpdate;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Jantinnerezo\LivewireAlert\LivewireAlert;

class ShowChanges extends Component
{

    use LivewireAlert;

    public $old_values;
    public $new_values;
    public $business_id;

    public function mount($businessId)
    {
        $this->business_id = decrypt($businessId);
        $business = BusinessUpdate::find($this->business_id);
        $this->old_values = $business->old_values;

        /**
         * TODO (Mang'erere): Show new values id fields as names instead of ids
         */
        $this->new_values = json_decode($business->new_values);
    }


    public function approve()
    {
		DB::beginTransaction();
        try{
            $business_information_data = $this->new_values->business_information;
            $business_location_data = $this->new_values->business_location;
            $business_bank_data = $this->new_values->business_bank;



            $this->business_location->update($business_location_data);
            $this->business_bank->update($business_bank_data);
            $this->business->update($business_information_data);
            DB::commit();
        }catch(Exception $e){
            DB::rollBack();
            Log::error($e);
            $this->alert('error', 'Something went wrong');
        }
    }



    public function render()
    {
        return view('livewire.business.updates.show');
    }
}
