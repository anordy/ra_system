<?php

namespace App\Http\Livewire\TaxAgent;

use App\Events\SendMail;
use App\Events\SendSms;
use App\Models\TaPaymentConfiguration;
use App\Models\TaxAgent;
use App\Models\TaxAgentStatus;
use App\Models\Taxpayer;
use App\Models\User;
use App\Notifications\DatabaseNotification;
use App\Services\ZanMalipo\ZmCore;
use Carbon\Carbon;
use Exception;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Livewire\Component;
use Jantinnerezo\LivewireAlert\LivewireAlert;

class Actions extends Component
{
	use LivewireAlert;

	public $taxagent;

	protected $listeners = [
		'confirmed',
		'toggleStatus'
	];

	public function approve()
	{
		$this->alert('success', 'Please add comment below to approve the registration payment request', [
			'position' => 'center',
			'toast' => false,
			'showConfirmButton' => true,
			'confirmButtonText' => 'Approve',
			'onConfirmed' => 'toggleStatus',
			'showCancelButton' => true,
			'cancelButtonText' => 'Cancel',
            'confirmButtonColor' => '#3085d6',
            'cancelButtonColor' => '#d33',
            'timer' => null,
            'input' => 'textarea',
			'data' => [
				'id' => $this->taxagent->id
			],
		]);

	}

	public function reject()
	{
		$this->alert('warning', 'Please add comment below to reject the registration payment request', [
			'position' => 'center',
			'toast' => false,
			'showConfirmButton' => true,
			'confirmButtonText' => 'Reject',
			'onConfirmed' => 'confirmed',
			'showCancelButton' => true,
			'cancelButtonText' => 'Cancel',
            'confirmButtonColor' => '#3085d6',
            'cancelButtonColor' => '#d33',
            'timer' => null,
            'input' => 'textarea',
			'data' => [
				'id' => $this->taxagent->id
			],
		]);
	}

	public function toggleStatus($value)
	{
		DB::beginTransaction();
		try {
			$data = (object) $value['data'];
			$agent = TaxAgent::query()->find($data->id);
			$agent->status = TaxAgentStatus::APPROVED;
			$agent->app_true_comment = $value['value'];
			$agent->app_first_date = Carbon::now();
			$agent->app_expire_date = Carbon::now()->addYear()->toDateTimeString();
			$agent->approver_id = Auth::id();
			$agent->approved_at = now();
			$agent->save();

            $agent->generateReferenceNo();

            $taxpayer = Taxpayer::find($this->taxagent->taxpayer_id);
			$taxpayer->notify(new DatabaseNotification(
				$subject = 'TAX-AGENT APPROVAL',
				$message = 'Your application has been approved',
				$href = 'taxagent.apply',
				$hrefText = 'view'
			));

			DB::commit();
			$this->flash('success', 'Request approved successfully');
			return redirect()->route('taxagents.requests');
		} catch (Exception $e) {
			DB::rollBack();
			Log::error($e);
			report($e);
			$this->alert('warning', 'Something went wrong!!!', ['onConfirmed' => 'confirmed', 'timer' => 2000]);
			redirect()->back()->getTargetUrl();
		}
	}


	public function confirmed($value)
	{
		DB::beginTransaction();
		try {
			$data = (object) $value['data'];
			$agent = TaxAgent::find($data->id);
			$agent->status = TaxAgentStatus::REJECTED;
            $agent->app_reject_comment = $value['value'];
            $agent->approver_id = Auth::id();
            $agent->final_rejected_at = now();
			$agent->save();

			$taxpayer = Taxpayer::find($agent->taxpayer_id);
			$taxpayer->notify(new DatabaseNotification(
				$subject = 'TAX-AGENT REJECTED',
				$message = 'Your application has been rejected',
				$href = 'taxagent.apply',
				$hrefText = 'view'
			));

			event(new SendMail('tax-agent-registration-approval', $agent->taxpayer_id));
			event(new SendSms('tax-agent-registration-approval', $agent->taxpayer_id));
			DB::commit();
			$this->flash('success', 'Request rejected successfully');
			return redirect()->route('taxagents.requests');
		} catch (Exception $e) {
			DB::rollBack();
			Log::error($e);
			report($e);
			$this->alert('warning', 'Something went wrong!!!', ['onConfirmed' => 'confirmed', 'timer' => 2000]);
			redirect()->back()->getTargetUrl();
		}
	}
	public function render()
	{
		return view('livewire.tax-agent.actions');
	}
}
