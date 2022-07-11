<?php

namespace App\Http\Livewire\TaxAgent;

use App\Events\SendMail;
use App\Events\SendSms;
use App\Models\Taxpayer;
use App\Notifications\DatabaseNotification;
use Carbon\Carbon;
use Illuminate\Database\Eloquent\Builder;
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
		return TaxAgent::query()->where('is_verified', '=', 0)->where('status', '=', 'pending');
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
		  Column::make('Verification', 'is_verified')
			->view('taxagents.includes.verification'),
		  Column::make('Action', 'id')
		    ->format(fn ($value) => <<< HTML
                    <button class="btn btn-info btn-sm" wire:click="approve($value)"><i class="fa fa-check"></i> </button>
                    <button class="btn btn-danger btn-sm" wire:click="reject($value)"><i class="bi bi-x-circle"></i> </button>
                HTML)
		    ->html(true),
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
			$agent->is_verified = 1;
			$agent->save();
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
