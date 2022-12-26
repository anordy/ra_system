<?php

namespace App\Http\Livewire\Mvr;

use App\Models\Audit;
use App\Models\MvrAgent;
use App\Models\MvrMotorVehicle;
use App\Models\MvrRegistrationStatus;
use App\Models\Taxpayer;
use App\Models\User;
use Illuminate\Database\Eloquent\Builder;
use Jantinnerezo\LivewireAlert\LivewireAlert;
use Rappasoft\LaravelLivewireTables\DataTableComponent;
use Rappasoft\LaravelLivewireTables\Views\Column;

class AgentsTable extends DataTableComponent
{
	use LivewireAlert;

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
                ->sortable(),
            Column::make("KYC no.", "taxpayer.reference_no")
                ->sortable(),
            Column::make("Name", "taxpayer_id")
                ->format(fn($taxpayer_id)=>Taxpayer::query()->find($taxpayer_id)->fullname())
                ->sortable(),
            Column::make("Phone Numbers", "taxpayer_id")
                ->format(function($taxpayer_id){
                    $taxpayer = Taxpayer::query()->find($taxpayer_id);
                    return $taxpayer->mobile.'/'.$taxpayer->alt_mobile;
                })
                ->sortable(),
            Column::make("email", "taxpayer.email")
                ->sortable(),
           Column::make("TIN", "taxpayer.tin")
                ->sortable(),
            Column::make("Reg. Date", "registration_date")
                ->sortable(),
            Column::make("Status", "status")
                ->sortable(),
            Column::make('Action', 'id')
                ->format(function ($value,$row) {
                    if ($row->status == 'ACTIVE') {
                        return <<< HTML
                        <button class="btn btn-info btn-sm" wire:click="changeStatus($row->id, '$row->status')"><i class="fa fa-lock-open"></i> </button>
                    HTML;
                    } else {
                        return <<< HTML
                        <button class="btn btn-danger btn-sm" wire:click="changeStatus($row->id, '$row->status')"><i class="fa fa-lock"></i> </button>
                    HTML;
                    }
                })
                ->html()
        ];
    }

    public function changeStatus($id, $status)
    {
        $this->alert('warning', 'Are you sure you want to change AGENT status ?', [
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
            $this->alert('warning', 'Something went wrong, please contact our support desk for help!!!', ['onConfirmed' => 'confirmed', 'timer' => 2000]);
        }
    }


}
