<?php

namespace App\Http\Livewire\TaxAgent\Renew;

use App\Models\BillingStatus;
use App\Models\ExchangeRate;
use App\Models\RenewTaxAgentRequest;
use App\Models\TaPaymentConfiguration;
use App\Models\TaxAgentStatus;
use App\Models\Taxpayer;
use App\Models\TaxType;
use App\Models\User;
use App\Notifications\DatabaseNotification;
use App\Services\ZanMalipo\ZmCore;
use App\Services\ZanMalipo\ZmResponse;
use Carbon\Carbon;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Jantinnerezo\LivewireAlert\LivewireAlert;
use Livewire\Component;

class ApproveAction extends Component
{
    use LivewireAlert;

    public $renew;

    protected $listeners = [
        'confirmed',
        'toggleStatus'
    ];

    public function approve()
    {
        $this->alert('warning', 'Are you sure you want to approve this request ?', [
            'position' => 'center',
            'toast' => false,
            'showConfirmButton' => true,
            'confirmButtonText' => 'Approve',
            'onConfirmed' => 'toggleStatus',
            'showCancelButton' => true,
            'cancelButtonText' => 'Cancel',
            'timer' => null,
            'data' => [
                'id' => $this->renew->id
            ],

        ]);
    }

    public function reject()
    {
        $this->alert('warning', 'Are you sure you want to approve this request ?', [
            'position' => 'center',
            'toast' => false,
            'showConfirmButton' => true,
            'confirmButtonText' => 'Reject',
            'onConfirmed' => 'confirmed',
            'showCancelButton' => true,
            'cancelButtonText' => 'Cancel',
            'timer' => null,
            'data' => [
                'id' => $this->renew->id
            ],

        ]);
    }

    public function toggleStatus($value)
    {
        DB::beginTransaction();
        try {
            $data = (object)$value['data'];
            $req = RenewTaxAgentRequest::query()->find($data->id);

            $fee = TaPaymentConfiguration::query()->select('id', 'amount', 'category', 'is_citizen', 'currency', 'duration')
                ->where('category', 'Renewal Fee')
                ->where('is_citizen', $req->tax_agent->taxpayer->is_citizen)
                ->first();
                
            $req->status = TaxAgentStatus::APPROVED;
            $req->renew_first_date = Carbon::now();
            $req->renew_expire_date = Carbon::now()->addYear($fee->duration)->toDateTimeString();
            $req->approved_at = now();
            $req->save();

            $taxpayer = Taxpayer::query()->find($req->tax_agent->id);
            $taxpayer->notify(new DatabaseNotification(
                $message = 'Tax agent renew ',
                $type = 'info',
                $messageLong = 'Your request for tax agent renew has been approved successfully',
                $href = '/taxagent/apply',
                $hrefText = 'View'
            ));

            DB::commit();
            $this->flash('success', 'Request approved successfully');
            return redirect()->route('taxagents.renew');

        } catch (Exception $e) {
            DB::rollBack();
            Log::error($e);
            report($e);
            $this->alert('warning', 'Something went wrong!!!', ['onConfirmed' => 'confirmed', 'timer' => 2000]);
        }
    }

    public function confirmed($value)
    {
        DB::beginTransaction();
        try {
            $data = (object)$value['data'];
            $req = RenewTaxAgentRequest::query()->find($data->id);
            $req->status = TaxAgentStatus::REJECTED;
            $req->app_reject_comment = 'The request is invalid';
            $req->rejected_by_id = Auth::id();
            $req->rejected_at = now();
            $req->save();

            $taxpayer = Taxpayer::query()->find($req->tax_agent->id);
            $taxpayer->notify(new DatabaseNotification(
                $message = 'Tax agent renew ',
                $type = 'info',
                $messageLong = 'Your request for tax agent renew has been rejected',
                $href = '/taxagent/apply',
                $hrefText = 'View'
            ));

            DB::commit();
            $this->flash('success', 'Request rejected successfully');
            return redirect()->route('taxagents.renew');

        } catch (Exception $e) {
            DB::rollBack();
            Log::error($e);
            report($e);
            $this->alert('warning', 'Something went wrong!!!', ['onConfirmed' => 'confirmed', 'timer' => 2000]);
        }
    }

    public function render()
    {
        return view('livewire.tax-agent.renew.verify-action');
    }
}
