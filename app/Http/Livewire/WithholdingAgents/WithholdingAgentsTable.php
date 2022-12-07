<?php

namespace App\Http\Livewire\WithholdingAgents;

use id;
use Exception;
use Carbon\Carbon;
use App\Models\WithholdingAgent;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Support\Facades\Log;
use Jantinnerezo\LivewireAlert\LivewireAlert;
use Rappasoft\LaravelLivewireTables\Views\Column;
use Rappasoft\LaravelLivewireTables\DataTableComponent;

class WithholdingAgentsTable extends DataTableComponent
{
    use LivewireAlert;

    public function configure(): void
    {
        $this->setPrimaryKey('id');
        $this->setTableWrapperAttributes([
            'default' => true,
            'class' => 'table-bordered table-sm',
        ]);
    }

    public function builder(): Builder
    {
        return WithholdingAgent::query()->orderBy('withholding_agents.created_at', 'DESC');
    }

    protected $listeners = [
        'confirmed',
    ];

    public function columns(): array
    {
        return [
            Column::make('WA Number', 'wa_number')
                ->sortable()
                ->searchable(),
            Column::make('Institution Name', 'institution_name')
                ->sortable()
                ->searchable(),
            Column::make('E-mail', 'email')
                ->sortable()
                ->searchable(),
            Column::make('Mobile', 'mobile')
                ->sortable()
                ->searchable(),
            Column::make('Commencing Date', 'date_of_commencing')
                ->format(function($value, $row) { return Carbon::create($row->date_of_commencing)->toFormattedDateString(); })
                ->sortable(),
            Column::make('Status', 'status')
                ->format(function ($value, $row) {
                        if ($row->status == 'active') {
                                return <<< HTML
                                    <span class="badge badge-success">Active</span>
                            HTML;
                        } else if ($row->status == 'inactive') {
                            return <<< HTML
                            <span class="badge badge-danger">Inactive</span>
                            HTML; 
                        }
                })
                ->html(true),
            Column::make('Action', 'id')
                ->view('withholding-agent.actions'),
        ];
    }


    public function changeStatus($id)
    {
        $withholding_agent = WithholdingAgent::findOrFail($id);
        $status = $withholding_agent->status == 'active' ? 'Deactivate' : 'Activate';
        $this->alert('warning', "Are you sure you want to {$status} ?", [
            'position' => 'center',
            'toast' => false,
            'showConfirmButton' => true,
            'confirmButtonText' => $status,
            'onConfirmed' => 'confirmed',
            'showCancelButton' => true,
            'cancelButtonText' => 'Cancel',
            'timer' => null,
            'data' => [
                'id' => $id
            ],

        ]);
    }


    public function confirmed($value)
    {
        try {
            $data = (object) $value['data'];
            $withholding_agent = WithholdingAgent::findOrFail($data->id);
            if ($withholding_agent->status == 'active') {
                $withholding_agent->update([
                    'status' => 'inactive'
                ]);
            } else if ($withholding_agent->status == 'inactive') {
                $withholding_agent->update([
                    'status' => 'active'
                ]);
            }
            $this->flash('success', 'Status updated successfully', [], redirect()->back()->getTargetUrl());
        } catch (Exception $e) {
            Log::error($e);
            $this->alert('warning', 'Something whent wrong!!!', ['onConfirmed' => 'confirmed', 'timer' => 2000]);
        }
    }
}
