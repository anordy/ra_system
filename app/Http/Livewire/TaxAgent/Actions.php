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
				'id' => $this->taxagent->id
			],
		]);
	}

	public function reject($id)
	{
		$this->alert('warning', 'Are you sure you want to reject this request ?', [
			'position' => 'center',
			'toast' => false,
			'showConfirmButton' => true,
			'confirmButtonText' => 'Reject',
			'onConfirmed' => 'confirmed',
			'showCancelButton' => true,
			'cancelButtonText' => 'Cancel',
			'timer' => null,
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
			$agent = TaxAgent::find($data->id);
			$agent->status = TaxAgentStatus::APPROVED;
			$agent->reference_no = "ZRB10" . rand(0, 9999);
			$agent->app_first_date = Carbon::now();
			$agent->app_expire_date = Carbon::now()->addYear()->toDateTimeString();
			$agent->save();

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
			$agent->save();

			//notify the taxpayer
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
