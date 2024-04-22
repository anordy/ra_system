<?php

namespace App\Http\Livewire\Mvr;

use App\Enum\GeneralConstant;
use App\Models\MvrAgent;
use App\Traits\CustomAlert;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Support\Facades\Log;
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
        $this->customAlert(GeneralConstant::WARNING, 'Are you sure you want to change AGENT status ?', [
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
        } catch (\Exception $exception) {
            Log::error('MVR-AGENT-TABLE-TOGGLE-STATUS', [$exception]);
            $this->customAlert(GeneralConstant::WARNING, 'Something went wrong, please contact the administrator for help', ['onConfirmed' => 'confirmed', 'timer' => 2000]);
        }
    }


}
