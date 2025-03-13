<?php

namespace App\Http\Livewire\Incedent;

use App\Enum\RaStatus;
use App\Models\BankChannel;
use App\Models\BankSystem;
use App\Models\Currency;
use App\Models\Priority;
use App\Models\RaIncedent;
use App\Models\RaIssue;
use App\Models\User;
use App\Traits\CustomAlert;
use Exception;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Livewire\Component;
use Livewire\WithFileUploads;
use PhpOffice\PhpSpreadsheet\Calculation\DateTimeExcel\Current;

class Create extends Component
{
    use WithFileUploads,CustomAlert;

   public $priorities,$bankChannels,$systems,$users;
   public $name,$bankChannelId,$reportedBy,$ownerId,$reportDate,$impactRevenue,
           $impactCustomer,$impactSystem,$bankSystemId,$affectedRevenue,
           $actionTaken,$symptomIncedent,$additionRA,$revenuDetected,$revenuePrevented,$revenueRecovered,
           $overchargingDetected,$overchargingPrevented,$overchargingRecovered,$isRealIssue;
           public $submitted = false;
    public $leakages = [];
    public $currencies;


    public function mount()
    {
        $this->priorities = Priority::query()
        ->select(['id', 'name'])
        ->get();
        $this->bankChannels = BankChannel::query()
                        ->select(['id','name'])->get();
        $this->systems = BankSystem::query()
        ->select(['id','name'])->get();
        $this->users = User::query()
        ->select(['id','fname','lname'])->get();
        $this->currencies = Currency::query()
                    ->select('id','name','code')->get();
        $this->leakages = [
            [
                'type' => '',
                'currency' => '',
                'detected' => '',
                'prevented' => '',
                'recovered' => ''
            ],
        ];
    }


    public function submit()
    {


        $this->validate([
            'name'                 => ['required', 'string', 'max:255'],
            'isRealIssue'                 => ['nullable', 'boolean'],
            'bankChannelId'        => ['required', 'exists:bank_channels,id'],
            'reportedBy'           => ['required', 'exists:users,id'],
            'ownerId'              => ['required', 'exists:users,id'],
            'reportDate'           => ['required', 'date'],
            'impactRevenue'        => ['required', 'string', 'max:255'],
            'impactCustomer'       => ['required', 'string', 'max:255'],
            'impactSystem'         => ['required', 'string', 'max:255'],
            'bankSystemId'       => ['required', 'exists:bank_systems,id'],
            'affectedRevenue'      => ['required', 'string', 'max:255'],
            'actionTaken'          => ['required', 'string', 'max:1000'],
            'additionRA'           => ['nullable', 'string', 'max:1000'],
            'symptomIncedent'           => ['nullable', 'string', 'max:1000'],
            'revenuDetected'           => ['nullable', 'regex:/^[0-9,]*$/'],
            'revenuePrevented'     => ['nullable', 'regex:/^[0-9,]*$/'],
            'revenueRecovered'     => ['nullable', 'regex:/^[0-9,]*$/'],
            'overchargingDetected'         => ['nullable', 'regex:/^[0-9,]*$/'],
            'overchargingPrevented' => ['nullable', 'regex:/^[0-9,]*$/'],
            'overchargingRecovered'  => ['nullable', 'regex:/^[0-9,]*$/'],
            'leakages.*.type' =>  ['required'],
            'leakages.*.currency' =>  ['required'],
            'leakages.*.detected' =>  ['required'],
            'leakages.*.prevented' =>  ['required'],
            'leakages.*.recovered' =>  ['required'],
        ], [
            'name.required'                => __('Please provide name of the Incedent'),
            'bankChannelId.required'       => __('Bank channel is required'),
            'bankChannelId.exists'         => __('Invalid bank channel selected'),
            'reportedBy.required'          => __('Reporter is required'),
            'impactRevenue.required'          => __('Please Provide impact on Revenue'),
            'impactCustomer.required'          => __('Please Provide impact on Customer'),
            'impactSystem.required'          => __('Please provide Impact on System'),
            'actionTaken.required'            => __('Please provide action taken'),
            'ownerId.required'             => __('Owner is required'),
            'ownerId.exists'               => __('Invalid owner selected'),
            'reportDate.required'          => __('Report date is required'),
            'reportDate.date'              => __('Invalid date format'),
            'leakages.*.type'              => __('Please Choose Type'),
            'leakages.*.currency.required'              => __('Please provide currency'),
            'leakages.*.detected.required'              => __('Please provide detected'),
            'leakages.*.prevented.required'              => __('Please input prevented'),
            'leakages.*.recovered.required'              => __('Please input recovered'),
        ]);
        

        try {
            DB::beginTransaction();

          $payload = [
           'reference' => 'RA00001',
            'bank_channel_id' => $this->bankChannelId,
            'name' => $this->name,
            'real_issue' => $this->isRealIssue,
            'symptom_of_incident' => $this->symptomIncedent,
            'impact_revenue' => $this->impactRevenue,
            'impact_customers' => $this->impactCustomer,
            'impact_system' => $this->impactSystem,
            'bank_system_id' => $this->bankSystemId,
            'incident_reported_date' => $this->reportDate,
            'status' => RaStatus::APPROVED,
            'reported_by' => $this->reportedBy,
            'owner_by' => $this->ownerId,
            'affected_rev_stream' => $this->affectedRevenue,
            'action_taken' => $this->actionTaken,
            'additional_ra' => $this->additionRA
          ];
       $incedent = RaIncedent::create($payload);

          foreach ($this->leakages as $row) {
                RaIssue::create([
                    'ra_incident_id' => $incedent->id,
                    'type' => $row['type'],
                    'currency' => $row['currency'],
                    'detected' => str_ireplace(',', '', $row['detected']),
                    'prevented' => str_ireplace(',', '', $row['prevented']),
                    'recovered' => str_ireplace(',', '', $row['recovered']),
                ]);
            }

            DB::commit();
            $this->customAlert('success', __('Your request for deregistration of your motor vehicle has been submitted, please wait for approval'));
            return redirect()->route('ra.incedent.index');
        } catch (Exception $exception) {
            DB::rollBack();
            Log::error('INITIATE-REVENUE-INCEDENT-CREATE', [$exception]);
            $this->customAlert('error', __('Something went wrong!'));
        }
    }

    public function addEntry()
    {
        $this->leakages[] = [
            [
                'type' => '',
                'currency' => '',
                'detected' => '',
                'prevented' => '',
                'recovered' => ''
            ],
        ];
    }

    public function removeRow($i)
    {
        unset($this->leakages[$i]);
    }


    public function render()
    {
        return view('livewire.incedent.create');
    }
}
