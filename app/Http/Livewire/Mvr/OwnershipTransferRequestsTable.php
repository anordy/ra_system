<?php

namespace App\Http\Livewire\Mvr;

use App\Models\MvrAgent;
use App\Models\MvrMotorVehicle;
use App\Models\MvrOwnershipTransfer;
use App\Models\MvrPlateNumberStatus;
use App\Models\MvrRegistrationStatus;
use App\Models\MvrRequestStatus;
use App\Models\TaxAgentStatus;
use App\Models\Taxpayer;
use Illuminate\Database\Eloquent\Builder;
use App\Traits\CustomAlert;
use Rappasoft\LaravelLivewireTables\DataTableComponent;
use Rappasoft\LaravelLivewireTables\Views\Column;
use App\Models\TaxAgent;

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
            Column::make(__("Previous Owner"), "previous_owner.first_name")->searchable(),   
            Column::make(__("New Owner"), "new_owner.first_name")->searchable(),   
            Column::make("Status", "request_status.name")->sortable(),
            Column::make("Transfer Reason", "ownership_transfer_reason.name")->sortable(),
            Column::make('Action', 'id')->format(function ($value) {
                $url = route('mvr.transfer-ownership.show', encrypt($value));
                return <<< HTML
                    <a class="btn btn-outline-primary btn-sm" href="$url"><i class="fa fa-eye"></i>View</a>
                HTML;
            })->html()
            
        ];
    }



}
