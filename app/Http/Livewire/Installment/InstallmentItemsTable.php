<?php

namespace App\Http\Livewire\Installment;

use App\Models\Installment\InstallmentItem;
use Illuminate\Database\Eloquent\Builder;
use Jantinnerezo\LivewireAlert\LivewireAlert;
use Rappasoft\LaravelLivewireTables\DataTableComponent;
use Rappasoft\LaravelLivewireTables\Views\Column;

class InstallmentItemsTable extends DataTableComponent
{
    use LivewireAlert;

    public $installment;

    public function builder(): Builder
    {
        return InstallmentItem::where('installment_id', $this->installment->id)
            ->orderBy('created_at', 'DESC');
    }

    public function columns(): array
    {
        return [
            Column::make('Amount', 'amount')
                ->sortable()
                ->searchable(),
            Column::make('Currency', 'currency')
                ->sortable()
                ->searchable(),
            Column::make('Paid at', 'paid_at')
                ->sortable()
                ->searchable()
                ->format(function ($value){
                    return $value ?? '-';
                }),
        ];
    }

    public function configure(): void
    {
        $this->setPrimaryKey('id');
        $this->setTableWrapperAttributes([
            'default' => true,
            'class' => 'table-bordered table-sm',
        ]);
    }
}