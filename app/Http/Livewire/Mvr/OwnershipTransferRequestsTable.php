<?php

namespace App\Http\Livewire\Mvr;

use App\Models\MvrOwnershipTransfer;
use App\Models\MvrRegistrationTypeCategory;
use App\Models\MvrRequestStatus;
use App\Traits\CustomAlert;
use Illuminate\Database\Eloquent\Builder;
use Rappasoft\LaravelLivewireTables\DataTableComponent;
use Rappasoft\LaravelLivewireTables\Views\Column;

class OwnershipTransferRequestsTable extends DataTableComponent
{
	use CustomAlert;

    public $status_id;

	public function builder(): Builder
	{
        if (empty($this->status_id)){
            return MvrOwnershipTransfer::query();
        }else{
            return MvrOwnershipTransfer::query()->whereIn('mvr_request_status_id',[$this->status_id]);
        }
	}

    public function mount($status){
        $rq_status = MvrRequestStatus::query()->where(['name'=>$status])->first();
        $this->status_id = $rq_status->id ?? '';
    }

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
            Column::make("Received Date", "application_date")->sortable(),
            Column::make("Chassis No", "motor_vehicle.chassis.chassis_number")->sortable(),
            Column::make(__("Previous Owner"), "agent_taxpayer_id")
                ->format(function ($value, $row) {
                    return $row->previous_owner->fullname() ?? 'N/A';
                })
                ->searchable(),
            Column::make(__("New Owner"), "owner_taxpayer_id")
                ->format(function ($value, $row) {
                    return $row->new_owner->fullname() ?? 'N/A';
                })
                ->searchable(),
            Column::make("Status", "request_status.name")->sortable(),
            Column::make("Transfer Reason", "ownership_transfer_reason.name")->sortable(),
            Column::make(__('Action'), 'id')->view('mvr.transfer.includes.actions'),
        ];
    }
}
