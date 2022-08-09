<?php

namespace App\Http\Livewire\Relief;

use App\Models\Relief\ReliefMinistry;
use App\Models\Relief\ReliefProject;
use Illuminate\Database\Eloquent\Builder;
use Rappasoft\LaravelLivewireTables\DataTableComponent;
use Rappasoft\LaravelLivewireTables\Views\Column;
use Jantinnerezo\LivewireAlert\LivewireAlert;

class ReliefMinistriesTable extends DataTableComponent
{
    use LivewireAlert;

    public function builder(): Builder
    {
        return ReliefMinistry::query();
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
            Column::make("Name", "name")
                ->sortable(),
            Column::make('Action', 'id')
                ->format(fn ($value) => <<< HTML
                    <button class="btn btn-info btn-sm" onclick="Livewire.emit('showModal', 'relief.relief-ministries-edit-modal',$value)"><i class="fa fa-edit"></i> </button>
                    <button class="btn btn-danger btn-sm" wire:click="delete($value)"><i class="fa fa-trash"></i> </button>
                HTML)
                ->html(true),
        ];
    }
}
