<?php

namespace App\Http\Livewire\Approval;

use App\Enum\InternalInfoChangeStatus;
use App\Enum\InternalInfoType;
use App\Models\BusinessHotel;
use App\Models\HotelStar;
use Exception;
use Livewire\Component;
use App\Traits\CustomAlert;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use App\Traits\WorkflowProcesssingTrait;
use Carbon\Carbon;

class InternalBusinessInfoChangeProcessing extends Component
{
    use WorkflowProcesssingTrait, CustomAlert;

    public $modelId;
    public $modelName;
    public $comments;

    public $info, $newHotelStar;
    public $hotelStars = [];

    public function mount($modelName, $modelId)
    {
        $this->modelName = $modelName;
        $this->modelId   = decrypt($modelId);
        $this->info = $modelName::findOrFail($this->modelId);

        $this->registerWorkflow($modelName, $this->modelId);

        // Load Hotel stars data
        if ($this->info->type === InternalInfoType::HOTEL_STARS) {
            $this->hotelStars = HotelStar::select('id', 'no_of_stars')->get();
            $this->newHotelStar = json_decode($this->info->new_values)->id;
        }

    }


    public function approve($transition)
    {
        $transition = $transition['data']['transition'];

        $this->validate([
            'comments' => 'required|string|strip_tag',
        ]);

        try {
            DB::beginTransaction();

            if ($this->checkTransition('registration_manager_review')) {
               $this->validate(['newHotelStar' => 'required']);
            }

            if ($this->checkTransition('director_of_trai_review')) {
                
                // Update Hotel Star Rating
                if ($this->subject->type === InternalInfoType::HOTEL_STARS) {
                    $businessHotel = BusinessHotel::where('location_id', $this->subject->location_id)->firstOrFail();
                    $businessHotel->update(['hotel_star_id' => json_decode($this->subject->new_values)->id]);
                }

                // Future: Update ISIC Codes

                // Future: Update Effective Date

                $this->subject->status = InternalInfoChangeStatus::APPROVED;
                $this->subject->approved_on = Carbon::now();
                $this->subject->save();
            }

            $this->doTransition($transition, ['status' => 'agree', 'comment' => $this->comments]);

            DB::commit();

            $this->flash('success', 'Application Approved Successful', [], redirect()->back()->getTargetUrl());
        } catch (Exception $e) {
            DB::rollBack();
            Log::error($e);
            $this->customAlert('error', 'Something went wrong, please contact the administrator for help');
        }
    }

    public function reject($transition)
    {
        $transition = $transition['data']['transition'];

        $this->validate([
            'comments' => 'required|string|strip_tag',
        ]);

        if ($this->checkTransition('director_of_trai_reject')) {

            try {
                $this->doTransition($transition, ['status' => 'agree', 'comment' => $this->comments]);
                $this->flash('success', 'Application sent for correction', [], redirect()->back()->getTargetUrl());
            } catch (Exception $e) {
                Log::error($e);
                $this->customAlert('error', 'Something went wrong, please contact the administrator for help');
            }
        }
    }


    protected $listeners = [
        'approve', 'reject'
    ];

    public function confirmPopUpModal($action, $transition)
    {
        $this->customAlert('warning', 'Are you sure you want to complete this action?', [
            'position' => 'center',
            'toast' => false,
            'showConfirmButton' => true,
            'confirmButtonText' => 'Confirm',
            'onConfirmed' => $action,
            'showCancelButton' => true,
            'cancelButtonText' => 'Cancel',
            'timer' => null,
            'data' => [
                'transition' => $transition
            ],

        ]);
    }

    public function render()
    {
        return view('livewire.approval.internal-business-change-processing');
    }
}
