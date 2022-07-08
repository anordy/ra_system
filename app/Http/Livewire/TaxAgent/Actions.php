<?php

namespace App\Http\Livewire\TaxAgent;

use App\Events\SendMail;
use App\Events\SendSms;
use App\Models\TaPaymentConfiguration;
use App\Models\TaxAgent;
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
			$agent->status = 'approved';
			$agent->reference_no = "ZRB10" . rand(0, 9999);
			$agent->app_first_date = Carbon::now();
			$agent->app_expire_date = Carbon::now()->addMonth()->toDateTimeString();
			$agent->save();

			$taxpayer = Taxpayer::find($this->taxagent->taxpayer_id);
			$taxpayer->notify(new DatabaseNotification(
				$subject = 'TAX-AGENT APPROVAL',
				$message = 'Your application has been approved',
				$href = 'taxagent.apply',
				$hrefText = 'view'
			));

			$fee = TaPaymentConfiguration::where('category', 'first fee')->first();
			$amount = $fee->amount;
			$expire_date = Carbon::now()->addMonth()->toDateTimeString();
			$billitems = [
				[
					'billable_id' => $agent->id,
					'billable_type' => get_class($agent),
					'fee_id' => $fee->id,
					'fee_type' => get_class($fee),
					'use_item_ref_on_pay' => 'N',
					'amount' => $amount,
					'currency' => 'TZS',
					'gfs_code' => '116101'
				]
			];
			$payer_type = get_class($taxpayer);
			$payer_name = implode(" ", array($taxpayer->first_name, $taxpayer->last_name));
			$payer_email = $taxpayer->email;
			$payer_phone = $taxpayer->mobile;
			$description = 'Tax agent registration fee';
			$payment_option = ZmCore::PAYMENT_OPTION_FULL;
			$currency = 'TZS';
			$createdby_type = get_class(User::find(Auth::id()));
			$exchange_rate = 0;
			$createdby_id = Auth::id();
			$payer_id = $taxpayer->id;

			try {
				$zmBill = ZmCore::createBill(
					$payer_id,
					$payer_type,
					$payer_name,
					$payer_email,
					$payer_phone,
					$expire_date,
					$description,
					$payment_option,
					$currency,
					$exchange_rate,
					$createdby_id,
					$createdby_type,
					$billitems
				);
				if (config('app.env') == 'local') {
					ZmCore::sendBill($zmBill->id);
				}
				$this->alert('success', 'saved successfully');
			} catch (\Throwable $exception) {
				Log::error($exception);
				$this->alert('error', 'something went wrong');
			}

			//			event(new SendMail('tax-agent-registration-approval', $agent->taxpayer_id));
			//			event(new SendSms('tax-agent-registration-approval', $agent->taxpayer_id));

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
			$agent->status = 'rejected';
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
