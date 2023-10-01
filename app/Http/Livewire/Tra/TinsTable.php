<?php

namespace App\Http\Livewire\Tra;

use App\Models\Tra\Tin;
use App\Traits\CustomAlert;
use Illuminate\Support\Facades\Gate;
use Rappasoft\LaravelLivewireTables\DataTableComponent;
use Rappasoft\LaravelLivewireTables\Views\Column;

class TinsTable extends DataTableComponent
{
    use CustomAlert;

    protected $model = Tin::class;
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

    public function columns(): array
    {
        return [
            Column::make('TIN', 'tin')
                ->sortable()
                ->searchable(),
            Column::make('First Name', 'first_name')
                ->sortable()
                ->searchable(),
            Column::make('Last Name', 'last_name')
                ->sortable()
                ->searchable(),
            Column::make('Taxpayer Name', 'taxpayer_name')
                ->sortable()
                ->searchable(),
            Column::make('Mobile', 'mobile')
                ->sortable()
                ->searchable(),
            Column::make('Email', 'email')
                ->sortable()
                ->searchable(),
            Column::make('Registration Date', 'registration_date')
                ->sortable()
                ->searchable(),
            Column::make('Received On', 'created_at')
                ->sortable()
                ->searchable(),
            Column::make('Status', 'tra_sync_status')
                ->format(function ($value, $row) {
                    if ($value == 0) {
                        return <<<HTML
                            <span style="border-radius: 0 !important;" class="badge badge-warning p-2" >Not Synced</span>
                        HTML;
                    } elseif ($value == 1) {
                        return <<<HTML
                            <span style="border-radius: 0 !important;" class="badge badge-success p-2" >Synced</span>
                        HTML;
                    }
                })
                ->html(),
            Column::make('Registered', 'tin_verification_status')
                ->format(function ($value, $row) {
                    if ($value === 'pending') {
                        return <<<HTML
                            <span style="border-radius: 0 !important;" class="badge badge-danger p-2" >Unregistered</span>
                        HTML;
                    } elseif ($value === 'approved') {
                        return <<<HTML
                            <span style="border-radius: 0 !important;" class="badge badge-success p-2" >Registered</span>
                        HTML;
                    }
                })
                ->html(),
        ];
    }

}
