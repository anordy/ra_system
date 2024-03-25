<?php

namespace App\Http\Livewire\Mvr;

use App\Models\Audit;
use App\Models\MvrAgent;
use App\Models\MvrMotorVehicle;
use App\Models\MvrRegistrationStatus;
use App\Models\Taxpayer;
use App\Models\User;
use Illuminate\Database\Eloquent\Builder;
use App\Traits\CustomAlert;
use Rappasoft\LaravelLivewireTables\DataTableComponent;
use Rappasoft\LaravelLivewireTables\Views\Column;

class AgentsTable extends DataTableComponent
{
	use CustomAlert;

    public function builder(): Builder
	{
        return MvrAgent::query();
    }

    protected $listeners = ['toggleStatus'];

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
            Column::make("Agent no.", "agent_number")
                ->searchable(),
            Column::make("Company Name", "company_name")
                ->format(fn($value, $row)=> $row->company_name ?? 'N/A')
                ->searchable(),
            Column::make("KYC no.", "taxpayer.reference_no")
                ->searchable(),
            Column::make("Name", "taxpayer_id")
                ->format(fn($value, $row)=> $row->taxpayer->fullname())
                ->searchable(),
            Column::make("email", "taxpayer.email"),
           Column::make("TIN", "taxpayer.tin")
                ->searchable(),
            Column::make("Reg. Date", "registration_date"),
            Column::make("Status", "status")
                ->sortable(),
            Column::make('Action', 'id')
                ->format(function ($value,$row) {
                    if ($row->status == 'ACTIVE') {
                        return <<< HTML
                        <button class="btn btn-info btn-sm" wire:click="changeStatus($row->id, '$row->status')"><i class="bi bi-unlock-fill"></i> </button>
                    HTML;
                    } else {
                        return <<< HTML
                        <button class="btn btn-danger btn-sm" wire:click="changeStatus($row->id, '$row->status')"><i class="bi bi-lock-fill"></i> </button>
                    HTML;
                    }
                })
                ->html()
        ];
    }

    public function changeStatus($id, $status)
    {
        $this->customAlert('warning', 'Are you sure you want to change AGENT status ?', [
            'position' => 'center',
            'toast' => false,
            'showConfirmButton' => true,
            'confirmButtonText' => $status == 'INACTIVE' ? 'Activate' : 'Deactivate',
            'onConfirmed' => 'toggleStatus',
            'showCancelButton' => true,
            'cancelButtonText' => 'Cancel',
            'timer' => null,
            'data' => [
                'id' => $id,
            ],

        ]);
    }

    public function toggleStatus($value)
    {
        try {
            $data = (object) $value['data'];
            $agent = MvrAgent::find($data->id);
            $agent->status = $agent->status == 'ACTIVE'?'INACTIVE':'ACTIVE';
            $agent->save();
            return redirect()->to(route('mvr.agent'));
        } catch (\Exception $e) {
            report($e);
            $this->customAlert('warning', 'Something went wrong, please contact the administrator for help', ['onConfirmed' => 'confirmed', 'timer' => 2000]);
        }
    }


}
