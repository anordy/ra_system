<?php

namespace App\Http\Livewire\Settings\InterestRate;

use App\Models\InterestRate;
use Exception;
use Illuminate\Database\Eloquent\Builder;
use Jantinnerezo\LivewireAlert\LivewireAlert;
use Rappasoft\LaravelLivewireTables\DataTableComponent;
use Rappasoft\LaravelLivewireTables\Views\Column;

class InterestRatesTable extends DataTableComponent
{
    use LivewireAlert;

    public function builder(): Builder
    {
        return  InterestRate::query()
            ->orderBy('year', 'Desc');
    }

    public function configure(): void
    {
        $this->setPrimaryKey('id');
        $this->setTableWrapperAttributes([
            'default' => true,
            'class' => 'table-bordered table-sm',
        ]);

        $this->setTdAttributes(function (Column $column, $row, $columnIndex, $rowIndex) {
            if ($column->isField('id')) {
                return [
                    'style' => 'width: 20%;',
                ];
            }

            return [];
        });
    }

    protected $listeners = [
        'confirmed'
    ];

    public function columns(): array
    {
        return [
            Column::make('Year', 'year')
                ->sortable()
                ->searchable(),
            Column::make('Rate', 'rate')
                ->sortable()
                ->searchable(),
            Column::make('Action', 'id')
                ->format(fn ($value) => <<< HTML
                    <button class="btn btn-info btn-sm" onclick="Livewire.emit('showModal', 'settings.interest-rate.interest-rate-edit-modal',$value)"><i class="fa fa-edit"></i> </button>
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
            InterestRate::find($data->id)->delete();
            $this->flash('success', 'Record deleted successfully', [], redirect()->back()->getTargetUrl());
        } catch (Exception $e) {
            report($e);
            $this->alert('warning', 'Something whent wrong!!!', ['onConfirmed' => 'confirmed', 'timer' => 2000]);
        }
    }
}
