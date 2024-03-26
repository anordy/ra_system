<?php

namespace App\Http\Livewire\Mvr;

use App\Enum\BillStatus;
use App\Models\MvrAgent;
use App\Models\MvrMotorVehicle;
use App\Models\MvrOwnershipTransfer;
use App\Models\MvrPlateNumberStatus;
use App\Models\MvrRegistrationStatus;
use App\Models\MvrRegistrationTypeCategory;
use App\Models\MvrRequestStatus;
use App\Models\MvrTransferCategory;
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

        $this->setAdditionalSelects(['mvr_transfer_category_id', 'mvr_request_status_id']);

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
            Column::make('Action', 'id')->format(function ($value, $row) {
                $value = "'".encrypt($value)."'";
                $url = route('mvr.transfer-ownership.show', $value);
                if (in_array($row->transfer_category->name, [MvrRegistrationTypeCategory::MILITARY,
                    MvrRegistrationTypeCategory::CORPORATE, MvrRegistrationTypeCategory::DIPLOMAT,
                    MvrRegistrationTypeCategory::GOVERNMENT
                    ]) && $row->request_status->name === MvrRequestStatus::STATUS_RC_ACCEPTED) {
                    return <<< HTML
                                        <button class="btn btn-outline-primary btn-sm" onclick="Livewire.emit('showModal', 'mvr.ownership-transfer-assign-plate-number', $value)">Assign Plate Number</button>
                                        <a class="btn btn-outline-primary btn-sm" href="$url">View</a>
                                    HTML;
                } else {
                    return <<< HTML
                    <a class="btn btn-outline-primary btn-sm" href="$url"><i class="fa fa-eye"></i>View</a>
                HTML;
                }

            })->html()
            
        ];
    }



}
