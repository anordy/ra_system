<?php

namespace App\Http\Livewire\Mvr\Blacklist;

use App\Enum\CustomMessage;
use App\Enum\GeneralConstant;
use App\Enum\Mvr\MvrBlacklistInitiatorType;
use App\Enum\Mvr\MvrBlacklistType;
use App\Enum\MvrRegistrationStatus;
use App\Models\DlDriversLicense;
use App\Models\MvrBlacklist;
use App\Models\MvrRegistration;
use App\Traits\CustomAlert;
use App\Traits\WorkflowProcesssingTrait;
use DB;
use Illuminate\Support\Facades\Storage;
use Livewire\Component;
use Livewire\WithFileUploads;
use Log;

class Initiate extends Component
{
    use CustomAlert, WorkflowProcesssingTrait, WithFileUploads;

    public $blackListType, $initiatorType, $blackListNumber, $reason, $evidenceFile, $blackListEntity, $blackListEntities = [];

    protected $rules = [
        'blackListType' => 'required|strip_tag',
        'reason' => 'required|strip_tag|max:255',
        'evidenceFile' => 'nullable|mimes:pdf,jpg,jpeg,png|max:3072',
    ];

    public function mount($initiatorType)
    {
        $this->initiatorType = $initiatorType;

        if ($this->initiatorType === MvrBlacklistInitiatorType::ZARTSA) {
            $this->blackListType = MvrBlacklistType::DL;
        }
    }

    public function updatedBlackListType()
    {
        $this->blackListEntity = null;
        $this->blackListNumber = null;
    }

    public function searchBlacklist()
    {
        $this->validate([
            'blackListNumber' => 'required|strip_tag|max:50'
        ]);

        try {
            if ($this->blackListType === MvrBlacklistType::MVR) {
                $this->blackListEntities = MvrRegistration::where('plate_number', $this->blackListNumber)
                    ->get();
            } else if ($this->blackListType === MvrBlacklistType::DL) {
                $this->blackListEntities = DlDriversLicense::where('license_number', $this->blackListNumber)->first();
            } else {
                $this->customAlert(GeneralConstant::WARNING, 'Invalid blacklist type');
                return;
            }

            $this->blackListEntity = $this->blackListEntities->first();

            if (!$this->blackListEntity) {
                $this->customAlert(GeneralConstant::WARNING, 'Invalid blacklist number or blacklist number not found');
                return;
            }
        } catch (\Exception $e) {
            Log::error('MVR-BLACKLIST-SEARCH-BLACKLIST', [$e]);
            $this->customAlert(GeneralConstant::ERROR, CustomMessage::ERROR);
        }
    }

    public function submit()
    {
        $this->validate();

        if (!$this->blackListEntity) {
            $this->customAlert(GeneralConstant::WARNING, 'Invalid blacklist number or blacklist number not found');
            return;
        }

        try {
            $filePath = null;

            if ($this->evidenceFile) {
                $filePath = $this->evidenceFile->store('mvr-blacklists', 'local');
            }

            DB::beginTransaction();

            $blacklist = MvrBlacklist::create([
                'reasons' => $this->reason,
                'type' => $this->blackListType,
                'evidence_path' => $filePath,
                'created_by' => \Auth::id(),
                'blacklist_type' => get_class($this->blackListEntity),
                'blacklist_id' => $this->blackListEntity->id,
                'initiator_type' => $this->initiatorType,
                'is_blocking' => !$this->blackListEntity->is_blocked,
            ]);

            if (!$blacklist) throw new \Exception('Failed creating blacklist');

            if ($this->initiatorType === MvrBlacklistInitiatorType::ZARTSA && $this->blackListType === MvrBlacklistType::DL) {
                if (!$this->blackListEntity->is_blocked) {
                    $this->customAlert(GeneralConstant::WARNING, 'This driver license is not blocked');
                    return;
                }

                $this->blackListEntities->update(['is_blocked' => !$this->blackListEntity->is_blocked]);
                $blacklist->status = MvrRegistrationStatus::PENDING;
                if (!$blacklist->save()) throw new \Exception('Failed updating MVR blacklist');

                $this->registerWorkflow(get_class($blacklist), $blacklist->id);
                $this->doTransition('application_submitted', ['status' => 'agree', 'comment' => null]);
            } else if ($this->initiatorType === MvrBlacklistInitiatorType::ZRA) {
                $this->blackListEntities->update(['is_blocked' => !$this->blackListEntity->is_blocked]);
                $blacklist->status = MvrRegistrationStatus::APPROVED;
                if (!$blacklist->save()) throw new \Exception('Failed updating MVR blacklist');
            } else {
                $this->customAlert(GeneralConstant::WARNING, 'Invalid initiator type');
                return;
            }

            DB::commit();

            $this->flash('success', 'Blacklist successfully created', [], redirect()->back()->getTargetUrl());
        } catch (\Exception $e) {
            DB::rollBack();
            if ($filePath && Storage::disk('local')->exists($filePath)) {
                Storage::disk('local')->delete($filePath);
            }
            Log::error('MVR-BLACKLIST-INITIATE', [$e]);
            $this->customAlert(GeneralConstant::ERROR, CustomMessage::ERROR);
        }
    }

    public function render()
    {
        return view('livewire.mvr.blacklist.initiate');
    }
}
