<?php

namespace App\Http\Livewire\TaxAgent;

use App\Events\SendMail;
use App\Events\SendSms;
use App\Models\TaPaymentConfiguration;
use App\Models\Taxpayer;
use App\Models\User;
use App\Models\ZmBill;
use App\Notifications\DatabaseNotification;
use Carbon\Carbon;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Jantinnerezo\LivewireAlert\LivewireAlert;
use Rappasoft\LaravelLivewireTables\DataTableComponent;
use Rappasoft\LaravelLivewireTables\Views\Column;
use App\Models\TaxAgent;
use App\Payment\SaveBill;

class TaxAgentTable extends DataTableComponent
{
	use LivewireAlert;

	protected $listeners = [
	  'confirmed',
	  'toggleStatus'
	];
//	protected $model = TaxAgent::class;

	public function builder(): Builder
	{
		return TaxAgent::query();
	}

	public function configure(): void
    {
        $this->setPrimaryKey('id');

	    $this->setTableWrapperAttributes([
	      'default' => true,
	      'class' => 'table-bordered table-sm',
	    ]);

//
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
	        Column::make('Status', 'is_verified')
          ->view('taxagents.includes.status'),
          Column::make('Action', 'id')
	        ->view('taxagents.includes.actionReq')

        ];
    }

	public function approve($id)
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
			$agent->is_verified = 1;
			$agent->save();

			$taxpayer = Taxpayer::find($agent->taxpayer_id);
			$taxpayer->notify(new DatabaseNotification(
			  $message = 'Tax agent approved',
			  $type = 'info',
			  $messageLong = 'Your application has been approved successfully',
			  $href = '/taxagent/apply',
			  $hrefText = 'View'
			));


			$fee = TaPaymentConfiguration::where('category', 'first fee')->first();
			$amount = $fee->amount;
			$date = Carbon::now()->addMonth();

//			SaveBill::savingBill($agent->taxpayer_id, $amount, $currency, $rate, $eq_amount, $control_no, $date, $name, $phone, $email, $description, $payment_option, $status, $zan_status);
			$bill = new ZmBill();
			$bill->amount = $amount;
			$bill->currency = 'TZS';
			$bill->exchange_rate = 0;
			$bill->equivalent_amount = 0;
			$bill->expire_on = $date;
			$bill->payer_name = implode(" ", array($taxpayer->first_name, $taxpayer->last_name));
			$bill->payer_phone_number = $taxpayer->mobile;
			$bill->payer_email = $taxpayer->email;
			$bill->description = 'popww n';
			$bill->payment_option = 1;
			$bill->user_type = get_class($user);
			$bill->user_id = $user->id;

			event(new SendMail('tax-agent-registration-approval', $agent->taxpayer_id));
			event(new SendSms('tax-agent-registration-approval', $agent->taxpayer_id));

			DB::commit();
			$this->flash('success', 'Request approved successfully', [], redirect()->back()->getTargetUrl());

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
			$data = (object) $value['data'];
			$agent = TaxAgent::find($data->id);
			$agent->is_verified = 2;
			$agent->save();


			$taxpayer = Taxpayer::find($agent->taxpayer_id);
			$taxpayer->notify(new DatabaseNotification(
			  $message = 'Tax agent rejected',
			  $type = 'info',
			  $messageLong = 'Your application has been rejected',
			  $href = '/taxagent/apply',
			  $hrefText = 'View'
			));

			event(new SendMail('tax-agent-registration-approval', $agent->taxpayer_id));
			event(new SendSms('tax-agent-registration-approval', $agent->taxpayer_id));
			DB::commit();
			$this->flash('success', 'Request rejected successfully', [], redirect()->back()->getTargetUrl());

		} catch (Exception $e) {
			DB::rollBack();
			Log::error($e);
			report($e);
			$this->alert('warning', 'Something went wrong!!!', ['onConfirmed' => 'confirmed', 'timer' => 2000]);
		}
	}

}
