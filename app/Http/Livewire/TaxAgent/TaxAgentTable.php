<?php

namespace App\Http\Livewire\TaxAgent;

use App\Events\SendMail;
use App\Events\SendSms;
use App\Models\Taxpayer;
use App\Notifications\DatabaseNotification;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Support\Facades\Auth;
use Jantinnerezo\LivewireAlert\LivewireAlert;
use Rappasoft\LaravelLivewireTables\DataTableComponent;
use Rappasoft\LaravelLivewireTables\Views\Column;
use App\Models\TaxAgent;

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

			event(new SendMail('tax-agent-registration-approval', $agent->taxpayer_id));
			event(new SendSms('tax-agent-registration-approval', $agent->taxpayer_id));

			$this->flash('success', 'Request approved successfully', [], redirect()->back()->getTargetUrl());

		} catch (Exception $e) {
			report($e);
			$this->alert('warning', 'Something whent wrong!!!', ['onConfirmed' => 'confirmed', 'timer' => 2000]);
		}
	}


	public function confirmed($value)
	{
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

			$this->flash('success', 'Request rejected successfully', [], redirect()->back()->getTargetUrl());

		} catch (Exception $e) {
			report($e);
			$this->alert('warning', 'Something whent wrong!!!', ['onConfirmed' => 'confirmed', 'timer' => 2000]);
		}
	}

}
