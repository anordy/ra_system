<?php

namespace App\Http\Livewire\Mvr;

use App\Enum\GeneralConstant;
use App\Models\MvrPlateNumberCollection;
use App\Models\MvrPlateNumberStatus;
use App\Models\MvrRegistration;
use App\Models\MvrRegistrationStatus;
use App\Rules\ValidPhoneNo;
use App\Traits\CustomAlert;
use App\Traits\PhoneUtil;
use Exception;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Livewire\Component;

class PlateNumberCollectionModel extends Component
{

    use CustomAlert, PhoneUtil;

    public $mvr_registration_id;
    public $collection_date;
    public $collector_name;
    public $collector_phone;

    protected function rules()
    {
        return [
            'collector_phone' => ['required', 'string', new ValidPhoneNo()],
            'collector_name' => 'required|string|strip_tag',
            'collection_date' => 'required|date|before_or_equal:today',
        ];
    }


    protected $messages = [
        'collector_phone.size' => 'Provide mobile number formatted as 0XXXXXXXX e.g. 0760000000.'
    ];

    public function mount($mvr_registration_id)
    {
        $this->mvr_registration_id = decrypt($mvr_registration_id);
    }


    public function submit()
    {
        $this->validate();
        try {
            $mvr = MvrRegistration::query()->findOrFail($this->mvr_registration_id);

            DB::beginTransaction();
            MvrPlateNumberCollection::query()->create([
                'mvr_registration_id' => $this->mvr_registration_id,
                'collector_name' => $this->collector_name,
                'collector_phone' => $this->getNationalFormat($this->collector_phone),
                'collection_date'=>$this->collection_date,
            ]);

            $mvr->update([
                'mvr_plate_number_status' => MvrPlateNumberStatus::STATUS_ACTIVE,
                'status' => MvrRegistrationStatus::STATUS_REGISTERED
            ]);

            DB::commit();
            return redirect()->to(route('mvr.plate-numbers'));
        } catch (Exception $e) {
            DB::rollBack();
            Log::error('PLATE-NUMBER-COLLECTION-MODEL', [$e]);
            $this->customAlert(GeneralConstant::ERROR, 'Something went wrong, please contact the administrator for help');
        }
    }

    public function render()
    {
        return view('livewire.mvr.plate-number-collection-modal');
    }
}
