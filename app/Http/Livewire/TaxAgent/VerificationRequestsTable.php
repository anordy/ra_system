<?php

namespace App\Http\Livewire\TaxAgent;

use App\Events\SendMail;
use App\Events\SendSms;
use App\Models\TaPaymentConfiguration;
use App\Models\TaxAgentStatus;
use App\Models\Taxpayer;
use App\Models\User;
use App\Notifications\DatabaseNotification;
use App\Services\ZanMalipo\ZmCore;
use Carbon\Carbon;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Rappasoft\LaravelLivewireTables\DataTableComponent;
use Rappasoft\LaravelLivewireTables\Views\Column;
use App\Models\TaxAgent;
use Jantinnerezo\LivewireAlert\LivewireAlert;

class VerificationRequestsTable extends DataTableComponent
{
	use LivewireAlert;

	public function builder(): Builder
	{
		return TaxAgent::query()->where('status', '=', TaxAgentStatus::PENDING);
	}

	protected $listeners = [
	  'confirmed',
	  'toggleStatus'
	];

	public function configure(): void
	{
		$this->setPrimaryKey('id');

		$this->setTableWrapperAttributes([
		  'default' => true,
		  'class' => 'table-bordered table-sm',
		]);
	}

	public function columns(): array
	{
		return [
		  Column::make("TIN No", "tin_no")
			->sortable(),
		  Column::make("Town", "town")
			->sortable(),
		  Column::make("Region", "region")
			->sortable(),
		  Column::make("Created At", "created_at")
			->sortable(),
		  Column::make('Status', 'status')
			->view('taxagents.includes.status'),
		  Column::make('Action', 'id')
		    ->view('taxagents.includes.verAction')
		];
	}

	public function approve($id)
	{
		$this->alert('warning', 'Are you sure you want to verify this request ?', [
		  'position' => 'center',
		  'toast' => false,
		  'showConfirmButton' => true,
		  'confirmButtonText' => 'Verify',
		  'onConfirmed' => 'toggleStatus',
		  'showCancelButton' => true,
		  'cancelButtonText' => 'Cancel',
		  'timer' => null,
		  'data' => [
			'id' => $id
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
			'id' => $id
		  ],
		]);
	}

	public function toggleStatus($value)
	{
		DB::beginTransaction();
		try {
			$data = (object) $value['data'];
			$agent = TaxAgent::find($data->id);
			$agent->status = TaxAgentStatus::VERIFIED;
			$agent->save();

			$taxpayer = Taxpayer::find($agent->taxpayer_id);

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
			DB::commit();
			$this->flash('success', 'Request verified successfully');
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
			$agent->is_verified = 0;
			$agent->save();
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
}
