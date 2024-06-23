<?php

namespace App\Http\Livewire\Tra;

use App\Models\Tra\EfdmsReceipt;
use App\Traits\CustomAlert;
use Rappasoft\LaravelLivewireTables\DataTableComponent;
use Rappasoft\LaravelLivewireTables\Views\Column;

class VfdmsReceiptsTable extends DataTableComponent
{
    use CustomAlert;

    public function builder(): \Illuminate\Database\Eloquent\Builder
    {
        return EfdmsReceipt::query()->orderBy('created_at', 'desc');
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

    public function columns(): array
    {
        return [
            Column::make('Receipt Number', 'receipt_number')
                ->sortable()
                ->searchable(),
            Column::make('Receipt Date', 'receipt_date')
                ->sortable()
                ->searchable(),
            Column::make('Seller TIN', 'seller_tin')
                ->sortable()
                ->searchable(),
            Column::make('Seller VRN', 'seller_vrn')
                ->sortable()
                ->searchable(),
            Column::make('Verification Code', 'verification_code')
                ->sortable()
                ->searchable(),
            Column::make('Tax Excl.', 'total_tax_exclusive')
                ->sortable()
                ->searchable(),
            Column::make('Tax Incl.', 'total_tax_inclusive')
                ->sortable()
                ->searchable(),
            Column::make('Tax Amount', 'total_tax_amount')
                ->sortable()
                ->searchable(),
            Column::make('Cancelled', 'iscancelled')
                ->format(function ($value, $row) {
                    if ($value == 0) {
                        return <<<HTML
                            <span class="badge badge-success p-2 rounded-0" >No</span>
                        HTML;
                    } elseif ($value == 1) {
                        return <<<HTML
                            <span class="badge badge-danger p-2 rounded-0" >Yes</span>
                        HTML;
                    }
                })
                ->html(),
            Column::make('On hold', 'isonhold')
                ->format(function ($value, $row) {
                    if ($value == 0) {
                        return <<<HTML
                            <span class="badge badge-success p-2 rounded-0" >No</span>
                        HTML;
                    } elseif ($value == 1) {
                        return <<<HTML
                            <span class="badge badge-danger p-2 rounded-0" >Yes</span>
                        HTML;
                    }
                })
                ->html(),
            Column::make('Utilized', 'isutilized')
                ->format(function ($value, $row) {
                    if ($value == 0) {
                        return <<<HTML
                            <span class="badge badge-success p-2 rounded-0" >No</span>
                        HTML;
                    } elseif ($value == 1) {
                        return <<<HTML
                            <span class="badge badge-danger p-2 rounded-0" >Yes</span>
                        HTML;
                    }
                })
                ->html(),
            Column::make('Received On', 'created_at')
                ->sortable()
                ->searchable(),
        ];
    }


}
