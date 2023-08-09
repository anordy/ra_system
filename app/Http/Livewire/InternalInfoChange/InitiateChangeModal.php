<?php

namespace App\Http\Livewire\InternalInfoChange;

use Exception;
use Livewire\Component;
use App\Models\HotelStar;
use App\Traits\CustomAlert;
use App\Models\BusinessHotel;
use App\Models\BusinessLocation;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Auth;
use App\Models\InternalBusinessUpdate;
use App\Traits\WorkflowProcesssingTrait;

class InitiateChangeModal extends Component
{
    use CustomAlert, WorkflowProcesssingTrait;

    public $informationType;
    public $businessHotel, $newHotelStarId, $hotelStars = [];
    public $zin, $location;

    public function mount() {

    }

    protected function rules()
    {
        return [
            'informationType' => 'required',
            'zin' => 'required',
            'newHotelStarId' => 'required_if:informationType,hotelStars'
        ];
    }

    protected $messages = [
        'newHotelStarId.required_if' => 'Please select new hotel star rating',
    ];

    public function submit()
    {
        $this->validate();
        
        try{
            DB::beginTransaction();

            // Record data to be altered in Business hotel stars
            if ($this->informationType === 'hotelStars') {
                $selectedStar = HotelStar::select('id', 'no_of_stars')->findOrFail($this->newHotelStarId);

                $internalChange = InternalBusinessUpdate::create([
                    'business_id' => $this->location->business_id,
                    'location_id' => $this->location->id,
                    'type' => 'hotel_stars',
                    'triggered_by' => Auth::id(),
                    'old_values' => json_encode(['no_of_stars' => $this->businessHotel->star->no_of_stars, 'id' => $this->businessHotel->hotel_star_id]),
                    'new_values' => json_encode(['no_of_stars' => $selectedStar->no_of_stars, 'id' => $this->newHotelStarId]),
                ]);
            }
         
            DB::commit();

            $this->registerWorkflow(get_class($internalChange), $internalChange->id);
            $this->doTransition('registration_manager_review', ['status' => 'agree']);

            $this->flash('success', 'Data forwarded for approval', [], redirect()->back()->getTargetUrl());
        }catch(Exception $e){
            DB::rollBack();
            Log::error($e);
            $this->customAlert('error', 'Something went wrong, please contact the administrator for help');
        }
    }

    public function getZin()
    {
        $this->location = BusinessLocation::select('id', 'business_id', 'name')->with('business')->where('zin', trim($this->zin))->first();
        if ($this->location) {
            
            // Load hotel stars & Business hotel if hotelStars info type is selected
            if ($this->informationType === 'hotelStars') {
                $this->hotelStars = HotelStar::select('id', 'no_of_stars')->get();
                $this->businessHotel = BusinessHotel::select('id', 'location_id', 'hotel_star_id')->with('star')->where('location_id', $this->location->id)->first();;
            }

        } else {
            $this->customAlert('error', 'Invalid ZIN Number provided');
        }
    }

    public function render()
    {
        return view('livewire.internal-info-change.initiate');
    }
}
