<?php

namespace App\Http\Livewire\Tra;

use App\Models\Tra\ExitedGood;
use App\Traits\CustomAlert;
use Rappasoft\LaravelLivewireTables\DataTableComponent;
use Rappasoft\LaravelLivewireTables\Views\Column;

class ExitedGoodsTable extends DataTableComponent
{
    use CustomAlert;

    protected $model = ExitedGood::class;
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
            Column::make('TANSAD Number', 'tansad_number')
                ->sortable()
                ->searchable(),
            Column::make('Supplier TIN', 'supplier_tin_number')
                ->sortable()
                ->searchable(),
            Column::make('VRN', 'vat_registration_number')
                ->sortable()
                ->searchable(),
            Column::make('Value Excl Tax', 'value_excluding_tax')
                ->sortable()
                ->searchable(),
            Column::make('Tax Amount', 'tax_amount')
                ->sortable()
                ->searchable(),
            Column::make('Release Date', 'release_date')
                ->sortable()
                ->searchable(),
            Column::make('Invoice Number', 'invoice_number')
                ->sortable()
                ->searchable(),
            Column::make('Declaration Type', 'custom_declaration_types')
                ->sortable()
                ->searchable(),
            Column::make('Recieved On', 'created_at')
                ->sortable()
                ->searchable(),
        ];
    }


}
