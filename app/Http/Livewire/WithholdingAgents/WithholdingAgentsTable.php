<?php

namespace App\Http\Livewire\WithholdingAgents;

use id;
use Exception;
use Carbon\Carbon;
use App\Models\WithholdingAgent;
use Illuminate\Database\Eloquent\Builder;
use Jantinnerezo\LivewireAlert\LivewireAlert;
use Rappasoft\LaravelLivewireTables\Views\Column;
use Rappasoft\LaravelLivewireTables\DataTableComponent;

class WithholdingAgentsTable extends DataTableComponent
{
    use LivewireAlert;

    public function configure(): void
    {
        $this->setPrimaryKey('id');
        $this->setAdditionalSelects(['officer_id', 'responsible_person_id']);
        $this->setTableWrapperAttributes([
            'default' => true,
            'class' => 'table-sm',
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
            Column::make('Responsible Person', 'responsible_person_id')
                ->label(function($row) {
                    return "{$row->taxpayer->first_name} {$row->taxpayer->last_name}";
                })
                ->sortable()
                ->searchable(),
            Column::make('E-mail', 'email')
                ->sortable()
                ->searchable(),
            Column::make('Mobile', 'mobile')
                ->sortable()
                ->searchable(),
            Column::make('Approved By', 'officer_id')
            ->label(function($row) {
                return "{$row->user->fname} {$row->user->lname}";
            })
            ->html(true)
                ->sortable()
                ->searchable(),
            Column::make('Verified On', 'created_at')
                ->format(function($value, $row) { return Carbon::create($row->created_at)->toFormattedDateString(); })
                ->sortable(),
            Column::make('Commencing Date', 'date_of_commencing')
                ->format(function($value, $row) { return Carbon::create($row->date_of_commencing)->toFormattedDateString(); })
                ->sortable(),
            Column::make('Action', 'id')
                ->format(fn ($value) => <<< HTML
                    <button class="btn btn-info btn-sm" onclick="Livewire.emit('showModal', 'withholding-agents.withholding-agent-edit-modal',$value)"><i class="fa fa-edit"></i> </button>
                    <button class="btn btn-success btn-sm" onclick="Livewire.emit('showModal', 'withholding-agents.withholding-agent-view-modal',$value)"><i class="fa fa-eye"></i> </button>
                    <button class="btn btn-danger btn-sm" wire:click="delete($value)"><i class="fa fa-trash"></i> </button>
                HTML)
                ->html(true),
        ];
    }


    public function delete($id)
    {
        $this->alert('warning', 'Are you sure you want to delete ?', [
            'position' => 'center',
            'toast' => false,
            'showConfirmButton' => true,
            'confirmButtonText' => 'Delete',
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
            WithholdingAgent::find($data->id)->delete();
            $this->flash('success', 'Record deleted successfully', [], redirect()->back()->getTargetUrl());
        } catch (Exception $e) {
            report($e);
            $this->alert('warning', 'Something whent wrong!!!', ['onConfirmed' => 'confirmed', 'timer' => 2000]);
        }
    }
}
